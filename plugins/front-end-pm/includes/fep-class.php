<?php
//Main CLASS
if ( ! class_exists( "fep_main_class" ) ) {
	class fep_main_class {

		private static $instance;

		public $posted_new_message = false;
		public $posted_reply_message = false;
		public $posted_bulk_actions = false;
		public $posted_user_settings = false;
		public $have_error = false;
		public $errors;
		public $message;

		public static function init() {
			if ( ! self::$instance instanceof self ) {
				self::$instance = new self;
			}

			return self::$instance;
		}



		/******************************************MAIN DISPLAY BEGIN******************************************/

		//Display the proper contents
		public function main_shortcode_output() {
			global $user_ID;
			if ( $user_ID ) {

				if ( ! fep_current_user_can( 'access_message' ) ) {

					return "<div class='fep-error'>" . __( "You do not have permission to access message system", 'front-end-pm' ) . "</div>";
				}

				$this->Posted();
				//Add header
				$out = $this->Header();

				//Add Menu
				$out .= $this->Menu();

				//Start the guts of the display
				$switch = ( isset( $_GET['fepaction'] ) && $_GET['fepaction'] ) ? $_GET['fepaction'] : 'messagebox';

				switch ( $switch ) {
					case has_action( "fep_switch_{$switch}" ):
						ob_start();
						do_action( "fep_switch_{$switch}" );
						$out .= ob_get_contents();
						ob_end_clean();
						break;
					case 'newmessage':
						$out .= $this->new_message();
						break;
					case 'viewmessage':
						$out .= $this->view_message();
						break;
					case 'settings':
						$out .= $this->user_settings();
						break;
					case 'announcements':
						$out .= Fep_Announcement::init()->announcement_box();
						break;
					case 'view_announcement':
						$out .= Fep_Announcement::init()->view_announcement();
						break;
					//case 'directory': // See Fep_Directory Class
					//$out .= $this->directory();
					// break;
					case 'messagebox':
					default: //Message box is shown by Default
						$out .= $this->fep_message_box();
						break;
				}

				//Add footer
				$out .= $this->Footer();
			} else {
				$out = "<div class='fep-error'>" . sprintf( __( "You must <a href='%s'>login</a> to view your message.", 'front-end-pm' ), wp_login_url( get_permalink() ) ) . "</div>";
			}

			return apply_filters( 'fep_main_shortcode_output', $out );
		}

		/**
		 * Posted
		 */
		public function Posted() {
			$action = ! empty( $_POST['fep_action'] ) ? $_POST['fep_action'] : '';

			if ( ! $action ) {
				return;
			}

			switch ( $action ) {
				case has_action( "fep_posted_action_{$action}" ):
					do_action( "fep_posted_action_{$action}", $this );
					break;
				case 'newmessage' :
					if ( ! fep_current_user_can( 'send_new_message' ) ) {
						return;
					}

					$this->posted_new_message = true;

					$this->errors = Fep_Form::init()->validate_form_field();
					if ( count( $this->errors->get_error_messages() ) > 0 ) {
						$this->have_error = true;
					} else {
						if ( $message_id = fep_send_message() ) {
							$message = get_post( $message_id );

							if ( 'publish' == $message->post_status ) {
								$this->message = '<div class="fep-success">' . __( "Message successfully sent.", 'front-end-pm' ) . ' </div>';
							} else {
								$this->message = '<div class="fep-success">' . __( "Message successfully sent and waiting for admin moderation.", 'front-end-pm' ) . ' </div>';
							}
						} else {
							$this->message = '<div class="fep-error">' . __( "Something wrong. Please try again.", 'front-end-pm' ) . ' </div>';
						}
					}

					break;
				case 'reply' :
					$pID       = ! empty( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
					$parent_id = fep_get_parent_id( $pID );

					if ( ! fep_current_user_can( 'send_reply', $parent_id ) ) {
						return;
					}

					$this->posted_reply_message = true;

					$this->errors = Fep_Form::init()->validate_form_field( 'reply' );
					if ( count( $this->errors->get_error_messages() ) > 0 ) {
						$this->have_error = true;
					} else {
						if ( $message_id = fep_send_message() ) {
							$message = get_post( $message_id );

							if ( 'publish' == $message->post_status ) {
								$this->message = '<div class="fep-success">' . __( "Message successfully sent.", 'front-end-pm' ) . ' </div>';
							} else {
								$this->message = '<div class="fep-success">' . __( "Message successfully sent and waiting for admin moderation.", 'front-end-pm' ) . ' </div>';
							}
						} else {
							$this->message = '<div class="fep-error">' . __( "Something wrong. Please try again.", 'front-end-pm' ) . ' </div>';
						}
					}

					break;
				case 'bulk_action' :
					$posted_bulk_action = ! empty( $_POST['fep-bulk-action'] ) ? $_POST['fep-bulk-action'] : '';
					if ( ! $posted_bulk_action ) {
						return;
					}


					$this->posted_bulk_actions = true;

					$token = ! empty( $_POST['token'] ) ? $_POST['token'] : '';

					if ( ! fep_verify_nonce( $token, 'bulk_action' ) ) {
						$this->message = '<div class="fep-error">' . __( "Invalid Token. Please try again!", 'front-end-pm' ) . ' </div>';

						return;
					}

					if ( $bulk_action_return = Fep_Message::init()->bulk_action( $posted_bulk_action ) ) {
						$this->message = $bulk_action_return;
					}
					break;
				case 'settings' :
					$this->posted_user_settings = true;

					add_action( 'fep_action_form_validated', array( $this, 'settings_save' ), 10, 2 );

					$this->errors = Fep_Form::init()->validate_form_field( 'settings' );
					if ( count( $this->errors->get_error_messages() ) > 0 ) {
						$this->message = fep_error( $this->errors );
					} else {
						$this->message = '<div class="fep-success">' . __( "Settings successfully saved.", 'front-end-pm' ) . ' </div>';
					}

					break;
				default:
					do_action( "fep_posted_action", $this );
					break;

			}
		}

		/**
		 * @param $where
		 * @param $fields
		 */
		public function settings_save( $where, $fields ) {
			if ( 'settings' != $where ) {
				return;
			}

			if ( ! $fields || ! is_array( $fields ) ) {
				return;
			}

			$settings = array();

			foreach ( $fields as $field ) {
				$settings[ $field['name'] ] = $field['posted-value'];
			}
			$settings = apply_filters( 'fep_filter_user_settings_before_save', $settings );

			update_user_option( get_current_user_id(), 'FEP_user_options', $settings );
		}

		/**
		 * @return string
		 */
		public function Header() {

			$header = "<div id='fep-wrapper'>";

			return $header;
		}

		/**
		 * @return string
		 */
		public function Menu() {
			$menu = "<div id='fep-menu'>";
			ob_start();

			do_action( 'fep_menu_button' );

			echo "</div>";
			echo "<div id='fep-content'>";

			do_action( 'fep_display_before_content' );

			$menu .= ob_get_clean();

			return $menu;
		}

		/**
		 * @return string
		 */
		public function Footer() {
			$footer = '</div>'; //End content

			$footer .= "<div id='fep-footer'>";
			ob_start();
			do_action( 'fep_footer_note' );
			$footer .= ob_get_clean();

			$footer .= '</div>';//End Footer
			$footer .= '</div>'; //End main wrapper

			return $footer;
		}

		/**
		 * @param string $action
		 * @param bool $total_message
		 * @param bool $messages
		 *
		 * @return mixed|void
		 */
		public function fep_message_box( $action = '', $total_message = false, $messages = false ) {

			if ( ! $action ) {
				$action = ( ! empty( $_GET['fepaction'] ) ) ? $_GET['fepaction'] : 'messagebox';
			}
			$g_filter = ! empty( $_GET['fep-filter'] ) ? $_GET['fep-filter'] : '';

			$mess = '';
			if ( $this->posted_bulk_actions ) {

				if ( $this->message ) {
					$mess = $this->message;
				}
			}

			if ( false === $total_message ) {
				$total_message = fep_get_user_message_count( 'total' );
			}

			if ( false === $messages ) {
				$messages = Fep_Message::init()->user_messages( $action );
			}

			if ( ! $total_message ) {
				return "<div class='fep-error'>" . apply_filters( 'fep_filter_messagebox_empty', __( "No messages found.", 'front-end-pm' ), $action ) . "</div>";
			}
			ob_start();

			echo $mess;

			do_action( 'fep_display_before_messagebox', $action );

			?>
			<form class="fep-message-table form" method="post" action="">
			<div class="fep-table fep-action-table">
				<div>
					<div class="fep-bulk-action">
						<select name="fep-bulk-action">
							<option value=""><?php _e( 'Bulk action', 'front-end-pm' ); ?></option>
							<?php foreach ( Fep_Message::init()->get_table_bulk_actions() as $bulk_action => $bulk_action_display ) { ?>
								<option value="<?php echo $bulk_action; ?>"><?php echo $bulk_action_display; ?></option>
							<?php } ?>
						</select>
					</div>
					<div>
						<input type="hidden" name="token" value="<?php echo fep_create_nonce( 'bulk_action' ); ?>"/>
						<button type="submit" class="fep-button" name="fep_action"
						        value="bulk_action"><?php _e( 'Apply', 'front-end-pm' ); ?></button>
					</div>
					<div class="fep-loading-gif-div">
					</div>
					<div class="fep-filter">
						<select onchange="if (this.value) window.location.href=this.value">
							<option value="<?php echo esc_url( remove_query_arg( array(
								'feppage',
								'fep-filter'
							) ) ); ?>"><?php _e( 'Show all', 'front-end-pm' ); ?></option>
							<?php foreach ( Fep_Message::init()->get_table_filters() as $filter => $filter_display ) { ?>
								<option value="<?php echo esc_url( add_query_arg( array(
									'fep-filter' => $filter,
									'feppage'    => false
								) ) ); ?>" <?php selected( $g_filter, $filter ); ?>><?php echo $filter_display; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<?php if ( $messages->have_posts() ) { ?>
				<div id="fep-table" class="fep-table fep-odd-even"><?php
				while ( $messages->have_posts() ) {
					$messages->the_post(); ?>
					<div id="fep-message-<?php echo get_the_ID(); ?>" class="fep-table-row"><?php
						foreach ( Fep_Message::init()->get_table_columns() as $column => $display ) { ?>
							<div
								class="fep-column fep-column-<?php echo $column; ?>"><?php Fep_Message::init()->get_column_content( $column ); ?></div>
						<?php } ?>
					</div>
					<?php
				} //endwhile
				?></div><?php
				echo fep_pagination();
			} else {
				?>
				<div
					class="fep-error"><?php _e( 'No messages found. Try different filter.', 'front-end-pm' ); ?></div><?php
			}
			?></form><?php
			wp_reset_postdata();

			return apply_filters( 'fep_messagebox', ob_get_clean(), $action );
		}

		/**
		 * @return string
		 */
		public function user_settings() {
			$prefs = "<p><strong>" . __( "Set your preferences below", 'front-end-pm' ) . ":</strong></p>";
			if ( $this->posted_user_settings ) {
				$prefs .= $this->message;
			}

			$prefs .= Fep_Form::init()->form_field_output( 'settings' );

			return $prefs;
		}

		/**
		 * @return string
		 */
		public function new_message() {

			if ( ! fep_current_user_can( 'send_new_message' ) ) {
				return "<div class='fep-error'>" . __( "You do not have permission to send new message!", 'front-end-pm' ) . "</div>";
			}

			$html = '<h2>' . __( 'Send Message', 'front-end-pm' ) . '</h2>';
			if ( $this->posted_new_message ) {

				if ( $this->have_error ) {
					//$html .= fep_error($this->errors);
					$html .= Fep_Form::init()->form_field_output( 'newmessage', $this->errors );
				} else {
					$html .= $this->message;
				}
			} else {
				$html .= Fep_Form::init()->form_field_output();
			}

			return $html;
		}

		/**
		 * @return string
		 */
		public function view_message() {
			global $wpdb, $user_ID, $post;

			$pID = ! empty( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;

			if ( ! $pID || ! fep_current_user_can( 'view_message', $pID ) ) {
				return "<div class='fep-error'>" . __( "You do not have permission to view this message!", 'front-end-pm' ) . "</div>";
			}

			$parent_id = fep_get_parent_id( $pID );

			if ( 'threaded' == fep_get_option( 'message_view', 'threaded' ) ) {
				$message = fep_get_message( $parent_id );
				$replies = fep_get_replies( $parent_id );
			} else {
				$message = fep_get_message( $pID );
				$replies = '';
			}

			if ( ! $message ) {
				return "<div class='fep-error'>" . __( "You do not have permission to view this message!", 'front-end-pm' ) . "</div>";
			}

			$post = $message; //setup_postdata does not work properly if variable name is NOT $post !!!!!

			ob_start();
			setup_postdata( $post ); //setup_postdata does not work properly if variable name is NOT $post !!!!!
			//$read_class = fep_is_read() ? ' fep-hide-if-js' : '';
			$participants = get_post_meta( get_the_ID(), '_participants' );
			$par          = array();
			foreach ( $participants as $participant ) {
				$par[] = fep_get_userdata( $participant, 'display_name', 'id' );
			}
			fep_make_read();
			fep_make_read( true );
			?>
			<div class="fep-message">
				<div class="fep-message-title-heading"><?php the_title(); ?></div>
				<div class="fep-message-title-heading"><?php _e( "Participants", 'front-end-pm' ); ?>
					: <?php echo implode( ', ', $par ); ?></div>
				<div class="fep-per-message">
					<div class="fep-message-title">
						<span class="author"><?php the_author_meta( 'display_name' ); ?></span>
						<span class="date"><?php the_time(); ?></span>
					</div>
					<div class="fep-message-content">
						<?php the_content(); ?>
						<?php do_action( 'fep_display_after_parent_message' ); ?>
					</div>
				</div><?php

				if ( $replies && $replies->have_posts() ) {
					wp_enqueue_script( 'fep-replies-show-hide' );

					while ( $replies->have_posts() ) {
						$replies->the_post();
						$read_class = fep_is_read() ? ' fep-hide-if-js' : '';
						fep_make_read();
						fep_make_read( true ); ?>
						<div class="fep-per-message">
						<div class="fep-message-title">
							<span class="author"><?php the_author_meta( 'display_name' ); ?></span>
							<span class="date"><?php the_time(); ?></span>
						</div>
						<div class="fep-message-content<?php echo $read_class; ?>">
							<?php the_content(); ?>
							<?php do_action( 'fep_display_after_reply_message' ); ?>
						</div>
						</div><?php
					}
				}
				?>
			</div>
			<?php
			wp_reset_postdata();

			if ( ! fep_current_user_can( 'send_reply', $parent_id ) ) {
				echo "<div class='fep-error'>" . __( "You do not have permission to send reply to this message!", 'front-end-pm' ) . "</div>";
			} elseif ( $this->posted_reply_message ) {
				if ( $this->have_error ) {
					echo Fep_Form::init()->form_field_output( 'reply', $this->errors, array( 'fep_parent_id' => $parent_id ) );
				} else {
					echo $this->message;
				}
			} else {
				echo Fep_Form::init()->form_field_output( 'reply', '', array( 'fep_parent_id' => $parent_id ) );
			}

			return ob_get_clean();
		}

		/******************************************MAIN DISPLAY END******************************************/

	} //END CLASS
} //ENDIF

//ADD SHORTCODES
add_shortcode( 'front-end-pm', array( fep_main_class::init(), 'main_shortcode_output' ) ); //for FRONT END PM


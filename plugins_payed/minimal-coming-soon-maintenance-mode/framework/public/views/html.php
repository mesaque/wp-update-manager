<?php

/**
 * Renders the html template for the plugin.
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo stripslashes( $options['title'] ); ?></title>
<?php if ( isset( $options['favicon'] ) && ! empty( $options['favicon'] ) ) : ?>
<link rel="shortcut icon" href="<?php echo esc_url_raw( $options['favicon'] ); ?>" />
<?php endif; ?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo SIGNALS_CSMM_URL; ?>/framework/public/css/public.css" />
<script src='//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js'></script>
<script>
	WebFont.load( {
		google: {
			families: ['<?php echo $options["header_font"]; ?>', '<?php echo $options["secondary_font"]; ?>']
		}
	} );
</script>
<?php require_once SIGNALS_CSMM_PATH . '/framework/public/include/styles.php'; ?>
</head>
<body class="signals-plugin">
	<div class="maintenance-mode">
		<div class="s-container">
			<div class="content">
				<?php

					// Logo
					if ( ! empty( $options['logo'] ) ) {
						$signals_arrange['logo'] = '<div class="logo-container">' . "\r\n";
						$signals_arrange['logo'] .= '<img src="' . $options['logo'] . '" class="logo" />' . "\r\n";
						$signals_arrange['logo'] .= '</div>' . "\r\n";
					}

					// Header text
					if ( ! empty( $options['header_text'] ) ) {
						$signals_arrange['header'] = '<h1 class="header-text">' . stripslashes( nl2br( $options['header_text'] ) ) . '</h1>' . "\r\n";
					}

					// Secondary text
					if ( ! empty( $options['secondary_text'] ) ) {
						$signals_arrange['secondary'] = '<p class="secondary-text">' . stripslashes( nl2br( $options['secondary_text'] ) ) . '</p>' . "\r\n";
					}

					// Form
					if ( ! empty( $options['mailchimp_api'] ) && ! empty( $options['mailchimp_list'] ) ) {
						// Checking if the form is submitted or not
						if ( isset( $_POST['signals_email'] ) ) {
							// Processing begins
							$signals_email = strip_tags( $_POST['signals_email'] );

							if ( '' === $signals_email ) {
								$code 		= 'danger';
								$response 	= __( 'Please provide your email address.', 'signals' );
							} else {
								$signals_email = filter_var( strtolower( trim( $signals_email ) ), FILTER_SANITIZE_EMAIL );

								if ( strpos( $signals_email, '@' ) ) {
									require_once SIGNALS_CSMM_PATH . '/framework/admin/include/classes/class-mailchimp.php';

									$signals_connect 	= new Signals_MailChimp( $options['mailchimp_api'] );
									$signals_response 	= $signals_connect->call( 'lists/subscribe', array(
										'id'            => $options['mailchimp_list'],
										'email'         => array( 'email' => $signals_email ),
										'send_welcome'  => true
									) );


									// Showing message as per the response from the mailchimp server
									if ( isset( $signals_response['code'] ) && 214 !== $signals_response['code'] ) {
										$code 		= 'danger';
										$response 	= $options['message_wrong'];
									} elseif ( isset( $signals_response['code'] ) && 214 === $signals_response['code'] ) {
										$code 		= 'success';
										$response 	= $options['message_subscribed'];
									} else {
										$code 		= 'success';
										$response 	= $options['message_done'];
									}
								} else {
									$code 			= 'danger';
									$response 		= $options['message_noemail'];
								}
							}
						} // signals_email

						// Subscription form
						// Displaying errors as well if they are set
						$signals_arrange['form'] = '<div class="subscription">';

						if ( isset( $code ) && isset( $response ) ) {
							$signals_arrange['form'] .= '<div class="signals-alert signals-alert-' . $code . '">' . $response . '</div>';
						}

						$signals_arrange['form'] .= '<form role="form" method="post">
							<input type="text" name="signals_email" placeholder="' . esc_attr( $options['input_text'] ) . '">
							<input type="submit" name="submit" value="' . esc_attr( $options['button_text'] ) . '">
						</form>';

						// antispam text
						if ( ! empty( $options['antispam_text'] ) ) {
							// The best part, we don't do spam!
							$signals_arrange['form'] .= '<p class="anti-spam">' . stripslashes( $options['antispam_text'] ) . '</p>';
						}

						$signals_arrange['form'] .= '</div>';


					} // mailchimp_api && mailchimp_list

					// Custom HTML
					$signals_arrange['html'] = stripslashes( $options['custom_html'] );

					// Let's show the sections now!
					if ( isset( $options['arrange'] ) && '' != $options['arrange'] ) {
						$signals_sections = explode( ',', $options['arrange'] );
					} else {
						$signals_sections = array( 'logo', 'header', 'secondary', 'form', 'html' );
					}

					foreach ( $signals_sections as $signals_section ) {
						if ( isset( $signals_arrange[$signals_section] ) ) {
							echo $signals_arrange[$signals_section];
						}
					}

				?>
			</div><!-- .content -->
		</div><!-- .s-container -->
	</div><!-- .maintenance-mode -->

<?php

	// analytics
	if ( isset( $options['analytics'] ) && '' != $options['analytics'] ) {
		echo stripslashes( $options['analytics'] ) . "\r\n";
	}

?>

<!-- Maintenance Mode Plugin by 69signals (http://www.69signals.com) -->
<!-- We are a Creative Digital Marketplace. We love to weave the web, simple but amazing. We create flawless web and mobile applications. -->
</body>
</html>
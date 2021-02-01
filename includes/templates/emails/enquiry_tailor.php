<?php

/**

 * Enquiry notification emails.

 */

$formdata = $args['form_data'];



?>

<table class="main" width="100%" cellpadding="0" cellspacing="0">

	<tr>

		<td class="content-wrap aligncenter">

			<table width="100%" cellpadding="0" cellspacing="0">

				<tr>

					<td class="content-block">

						<h1 class="aligncenter">Nova proposta</h1>

					</td>

				</tr>

				<tr>

					<td class="content-block">

						<h3 class="aligncenter">Detalhes da proposta</h3>

					</td>

				</tr>

				<tr>

					<td class="content-block aligncenter">

						<table class="invoice">

							<tr>

								<td style="margin: 0; padding: 5px 0;" valign="top">

									<table class="invoice-items" cellpadding="0" cellspacing="0">

										<tr>

											<td>Roteiro</td>

											<td class="m_943869716973812143alignright" style="text-align: right;"><?=$formdata['trip_name']?></td>

										</tr> 

										<tr>

											<td>Cliente</td>

											<td class="m_943869716973812143alignright" style="text-align: right;"><?=$formdata['nome_cliente']?></td>

										</tr>

										<tr>

											<td>E-mail</td>

											<td class="m_943869716973812143alignright" style="text-align: right;"><?=$formdata['email_cliente']?></td>

										</tr> 

									</table>

								</td>

							</tr>

						</table>

					</td>

				</tr>

				<tr>

					<td class="content-block aligncenter">

						<a href="<?=$formdata['url_roteiro']?>"><?php _e( 'Veja o roteiro no site', 'wp-travel-engine' ); ?></a>

					</td>

				</tr>

			</table>

		</td>

	</tr>

</table>

<?php


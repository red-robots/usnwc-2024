
<script>
 function timestamp() { var response = document.getElementById("g-recaptcha-response"); if (response == null || response.value.trim() == "") {var elems = JSON.parse(document.getElementsByName("captcha_settings")[0].value);elems["ts"] = JSON.stringify(new Date().getTime());document.getElementsByName("captcha_settings")[0].value = JSON.stringify(elems); } } setInterval(timestamp, 500); 
</script>

<!--  ----------------------------------------------------------------------  -->
<!--  NOTE: Please add the following <FORM> element to your page.             -->
<!--  ----------------------------------------------------------------------  -->
<div class="gform_wrapper gravity-theme gform_legacy_markup_wrapper">
	<form action="https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8&orgId=00D400000007IQu" method="POST">
		<div class="gform_body gform-body">
			<ul class="gform_fields top_label form_sublabel_below description_above">
				<input type=hidden name='captcha_settings' value='{"keyname":"ww","fallback":"true","orgId":"00D400000007IQu","ts":""}'>
				<input type=hidden name="oid" value="00D400000007IQu">
				<input type=hidden name="retURL" value="https://center.whitewater.org/contact/events-inquiry/events-inquiry-confirmation/">

				<!--  ----------------------------------------------------------------------  -->
				<!--  NOTE: These fields are optional debugging elements. Please uncomment    -->
				<!--  these lines if you wish to test in debug mode.                          -->
				<!--  <input type="hidden" name="debug" value=0>                              -->
				<!--  <input type="hidden" name="debugEmail"                                  -->
				<!--  value="brenden@crmscenarios.com">                                       -->
				<!--  ----------------------------------------------------------------------  -->
				<div class="gfield">
					<label  class="gfield_label" for="first_name">First Name</label>
					<div class="ginput_container">
						<input class="medium" id="first_name" maxlength="40" name="first_name" size="20" type="text" required=true/>
					</div>
				</div>

				<div class="gfield">
					<label  class="gfield_label" for="last_name">Last Name</label>
					<input class="medium" id="last_name" maxlength="80" name="last_name" size="20" type="text" required=true />
				</div>

				<div class="gfield">
					<label class="gfield_label" for="company">Organization Name</label>
					<input class="medium" id="company" maxlength="40" name="company" size="20" type="text" required=true />
				</div>

				<div class="gfield">
					<label class="gfield_label" for="email">Email</label>
					<input class="medium" id="email" maxlength="80" name="email" size="20" type="text" required=true />
				</div>

				<div class="gfield">
					<label class="gfield_label" for="phone">Phone</label>
					<input class="medium" id="phone" maxlength="40" name="phone" size="20" type="text" required=true />
				</div>

				<div class="gfield">
					<label class="gfield_label">Event Date:</label>
					<div class="ginput_container">
						<span class="dateInput">
							<input  id="00N40000001qO1l" name="00N40000001qO1l" size="20" type="date" required=true placeholder="mm/dd/yyyy" />
						</span>
					</div>
				</div>

				<div class="gfield">
					<label class="gfield_label">Estimated Group Size</label>
					<input  id="00N40000001qNv4" name="00N40000001qNv4" size="20" type="text" required=true/>
				</div>

				<div class="gfield">
					<label class="gfield_label">Has your group visited the Whitewater Center previously?</label>
					<select  id="00NTN000001NxYH" name="00NTN000001NxYH" title="Has your group visited previously?">
						<option value="">--None--</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</div>

				<div class="gfield">
					<input id="00NTN000001NxZt" name="00NTN000001NxZt" type="checkbox" value="1" checked style="display: none;"/>
				</div>

				<div class="gfield">
					<label for="description">Please let us know what your group would like to do at the Whitewater Center:</label>
					<textarea name="description"></textarea>
				</div>

				<input type="hidden" id="lead_source" name="lead_source" value="Info Email" />

				<div class="g-recaptcha" data-sitekey="6LdQw5MaAAAAAKYH6qvsi_EArVCM751GSi9PXfZO"></div>

			</ul>
		</div>

		<div class="gform_footer top_label">
			<input type="submit" name="submit">
		</div>

	</form>
</div>

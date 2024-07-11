<script>
 function timestamp() { var response = document.getElementById("g-recaptcha-response"); if (response == null || response.value.trim() == "") {var elems = JSON.parse(document.getElementsByName("captcha_settings")[0].value);elems["ts"] = JSON.stringify(new Date().getTime());document.getElementsByName("captcha_settings")[0].value = JSON.stringify(elems); } } setInterval(timestamp, 500); 
</script>

<div class="gform_wrapper gform_legacy_markup_wrapper">
	<form action="https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8&orgId=00D400000007IQu" method="POST">


		<div class="gform_body gform-body">
			<ul class="gform_fields top_label form_sublabel_below description_above">

				<input type=hidden name='captcha_settings' value='{"keyname":"ww","fallback":"true","orgId":"00D400000007IQu","ts":""}'>
				<input type=hidden name="oid" value="00D400000007IQu">
				<input type=hidden name="retURL" value="https://center.whitewater.org/contact/events-inquiry/events-inquiry-confirmation/">

				<!--  ----------------------------------------------------------------------  -->
				<!--  NOTE: These fields are optional debugging elements. Please uncomment    -->
				<!--  these lines if you wish to test in debug mode.                          -->
				  <input type="hidden" name="debug" value=0>                              
				  <input type="hidden" name="debugEmail"                                  
				  value="brenden@crmscenarios.com">                                      
				<!--  ----------------------------------------------------------------------  -->
				<li class="gfield">
					<label class="gfield_label" for="first_name">First Name</label>
					<div class="ginput_container">
						<input class="medium"  id="first_name" maxlength="40" name="first_name" size="20" type="text" required=true/>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label" for="last_name">Last Name</label>
					<div class="ginput_container">
						<input class="medium" id="last_name" maxlength="80" name="last_name" size="20" type="text" required=true/>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label" for="company">Organization</label>
					<div class="ginput_container">
						<input class="medium" id="company" maxlength="40" name="company" size="20" type="text" required=truerequired=true/>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label" for="email">Email</label>
					<div class="ginput_container">
						<input class="medium" id="email" maxlength="80" name="email" size="20" type="text" required=true/>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label" for="phone">Phone</label>
					<div class="ginput_container">
						<input class="medium" id="phone" maxlength="40" name="phone" size="20" type="text" required=true/>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label">Requested Event Date:</label>
					<div class="ginput_container">
						<span class="dateInput">
							<input class="medium" id="00N40000001qO1l" name="00N40000001qO1l" size="20" type="date" required="true" placeholder="mm/dd/yyyy" />

						</span>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label">Is This Date Flexible?:</label>
					<div class="ginput_container">
						<select  id="00N5a00000D0Y8U" name="00N5a00000D0Y8U" title="Is This Date Flexible?" required=true>
							<option value="">--None--</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label">Group Size:</label>
					<div class="ginput_container">
						<input class="medium" id="00N40000001qNv4" name="00N40000001qNv4" size="20" type="text" required=true/>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label">Interest in Venue:</label>
					<div class="ginput_container">
						<select  id="00N40000001qNud" name="00N40000001qNud" title="Interest in Venue" required=true>
							<option value="">--None--</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label">Which activities are you interested in?:</label>
					<div class="ginput_container">
						<select style="min-width: 280px" id="00N5a00000D0Y8Z" multiple="multiple" name="00N5a00000D0Y8Z" title="Which activities are you interested in?" required=true>
							<option value="Day Passes">Day Passes</option>
							<option value="Team Development">Team Development</option>
							<option value="Educational Adventures">Educational Adventures</option>
						</select>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label">How would you prefer to be contacted?:</label>
					<div class="ginput_container">
						<select style="min-width: 280px" id="00N5a00000D0Y8e" multiple="multiple" name="00N5a00000D0Y8e" title="How would you prefer to be contacted?" required=true>
							<option value="Email">Email</option>
							<option value="Phone">Phone</option>
						</select>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label">Catering Required:</label>
					<div class="ginput_container">
						<select  id="00N40000001qO1g" name="00N40000001qO1g" title="Catering Required" required=true>
							<option value="">--None--</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
					</div>
				</li>

				<li class="gfield">
					<label class="gfield_label" for="description">Additional Information?</label>
					<div class="ginput_container">
						<textarea class="textarea medium" name="description"></textarea>
					</div>
				</li>

				<input type="hidden" id="lead_source" name="lead_source" value="Info Email" />

				<!-- <div class="g-recaptcha" data-sitekey="6Le9UacoAAAAAK7BxLYJU6iPz9Cmky05QmFRO9_o"></div> -->
				<div class="g-recaptcha" data-sitekey="6LdQw5MaAAAAAKYH6qvsi_EArVCM751GSi9PXfZO"></div>
			
			</ul>
		</div>

		<div class="gform_footer top_label">
			<input class="gform_button button" type="submit" name="submit">
		</div>

			
		
	</form>
</div>
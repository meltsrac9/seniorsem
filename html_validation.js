// Wait for the DOM to be ready
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='form']").validate({
    // Specify validation rules
    rules: {
			
			name:{
				required: true,
				minlength: 5
			},
			
			email:{
				required: true,
				email: true
			},
			
			number:{
				required: true
			},
			
			range:{
				required: true
			},
			
			paragraph:{
				required: true,
				minlength: 1,
				maxlength: 140
			},
			
			messages: {
				name: {
					required: "Please enter your name",
					minlength: "Name must be at least 5 characters long because I did not think this through as someone named Ray"
				},
				email: "Please enter a valid email address",
				number:{
					required: "Please select a number"
				},
				range:{
					required: "Please select a range value"
				},
				paragraph:{
					required: "Please enter some text",
					minlength: "Text must be at least 1 characters",
					maxlength: "Text cannot exceed 140 characters"
				}
			},
			submitHandler: function(form){
				form.submit();
			}     
		}
	});
});
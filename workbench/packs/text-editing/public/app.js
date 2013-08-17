//////////////////////////////////////////////////////////////////////////////////////////////////////////
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// BEFORE LOADING ALOHA, be sure to make following calls in your app
// this secures to not overwrite the actual jQuery namespace

// <script>
// Aloha = window.Aloha || {};
// Aloha.settings = Aloha.settings || {};
// Aloha.settings.jQuery = jQuery;
// </script>
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//////////////////////////////////////////////////////////////////////////////////////////////////////////




// disable the sidebar
Aloha.settings.sidebar = {
	disabled: true
}

// when the aloha editor is loaded
Aloha.ready( function() {
	// init the text editing
	TextEditing.init();
});

var TextEditing = {
	// stores the value if the dom insert trigger should execute an action
	triggerDOMChange: true,
	// stores the timeout for the dom inserted event
	timeout: window.setTimeout(function(){}, 0),
	// sotes the timeout for saving the new content
	timeoutSaveContent: window.setTimeout(function(){}, 0),

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// function to init the text editin functions
	// 
	init: function(){
		// init the click event for the text editing tags
		TextEditing.initElementEditing();

		// look for dom inserts and modifies, not possible in IE
		$('body').bind('DOMNodeInserted', function(event) {
			// check if we should react on the dom inserted event
			if(TextEditing.triggerDOMChange) {
				// clear the timeout
				window.clearTimeout(TextEditing.timeout);
				// reinit the click event for the text editing tags after some time
				TextEditing.timeout = window.setTimeout(function(){
					// init the click event for the text editing tags
					TextEditing.initElementEditing();
				}, 250);
			}
		});
	},

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// function to (re)init the text editing tag click event
	// 
	initElementEditing: function() {
		// deactivate the DOM inserted trigger event
		TextEditing.triggerDOMChange = false;

		// (re)init the aloha editor
		Aloha.jQuery('.textEditing').aloha();

		// reactivate the DOM insert trigger event
		TextEditing.triggerDOMChange = true;

		// when a user clicks on an editable field
		Aloha.bind('aloha-editable-activated', function() {
			// clear the timeout
			window.clearTimeout(TextEditing.timeout);
			// deactivate the DOM inserted trigger event
			TextEditing.triggerDOMChange = false;
		});

		// when a user leaves an editable field
		Aloha.bind('aloha-editable-deactivated', function(evt, args) {
			// reactivate the DOM insert trigger event
			TextEditing.triggerDOMChange = true;
			// clear the timeout
			window.clearTimeout(TextEditing.timeoutSaveContent);

			// store the element
			var el = args.editable.obj;

			// make the new timeout
			TextEditing.timeoutSaveContent = window.setTimeout(function(){
				// try to store the new text
				$.ajax({
					url: "textEditing/" + el.attr('data-key'),
					cache: false,
					type: "PUT",
					headers: { // pass the csrf token
						"csrf_token" : Config.CSFR_TOKEN
					},
					data: {
						locale: el.attr('data-locale'),
						content: el.html()
					},
					success: function(result){
						// show the user the success message
						alert(result.message);
						// update all other elements with the same key
						$('.textEditing[data-key="'+ el.attr('data-key') +'"]').html(result.content);
					},
					error: function(result){
						// show the user the error message
						alert(result.responseJSON.message);
					}
				});
			}, 50);
		});
	}
};
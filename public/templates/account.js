 jQuery(document).ready(function() {


 	//waiting for login endpoint

$(".login").click(function(argument) {
	
		login();
})

function login() {


	$.post( "example.php", function() {
  alert( "success" );
})
  .done(function() {
    alert( "second success" );
  })
  .fail(function() {
    alert( "error" );
  })
  .always(function() {
    alert( "finished" );
     localStorage.setItem('user','ugur');
       window.location.replace("main.html");
});

	
}

function register() {
	

}

function forgetPassword(argument) {

	
}

});
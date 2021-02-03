/*Author: Sam Gershkovich 000801766
  Date: December 02, 2020
 *I, Sam Gershkovich, 000801766 certify that this material is my original work.
 *No other person's work has been used without due acknowledgement.
 *
*/


window.addEventListener("load", function () {
    sessionStorage.clear();

    //Click listeners for every password visibility icons
    $(".visibility").each(function () {
        
        //When this is clicked
        $(this).click(function () {
            
            //Toggle between open and close eye icon
            $(this).toggleClass("fa-eye").toggleClass("fa-eye-slash");
            
            //Find the password input element
            let input = $(this).parent().find("input");
            
            //Toggle the input type between password and text
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            }
            else {
                input.attr("type", "password");
            }
        });
    });

    //When the login form is submitted
    document.forms[0].addEventListener("submit", function (event) {

        let form = this;

        //Disable multiple submissions of the form
        setTimeout(disable, 50);

        //Disable the login button and change the cursor to the wait cursor
        function disable() {
            form.submit.classList.toggle("waiting");
            document.querySelector("html").classList.toggle("disable-mouse");
        }

        event.preventDefault();

        //Verify the login attempt
        let url = "login.php?username=" + document.forms[0].username.value + "&password=" + document.forms[0].password.value;
        fetch(url, { credentials: "include" })
            .then(response => response.text())
            .then(success);

        function success(data) {

            //Set the login status
            $("#login-status").html(data);

            //Animate the login status
            $("#login-status").fadeOut(50).fadeIn(200);

            //If the login was successful
            if (data == "Success") {
                //Proceed to home page
                window.location.href = "home.php";
            }                        
        }
    });

});
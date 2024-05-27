function validateForm() {
    // error handling
    var errorCounter = 0;

    $(".required").each(function(i, obj) {

        if($(this).val() === ''){
            $(this).parent().addClass("has-error");
            errorCounter++;
        } else{ 
            $(this).parent().removeClass("has-error"); 
        }

    });
    
    return errorCounter;

}



function validateNoSpecialChars(inputElement) {
    // Listen for input events in the given input element
    $(inputElement).on("input", function() {
        // Get the entered text
        var enteredText = $(this).val();

        // Define a regular expression to allow hyphens and apostrophes
        var allowedCharacters = /^[a-zA-Z'\- ]*$/;

        // Check if the entered text contains only allowed characters
        if (allowedCharacters.test(enteredText)) {
            // Remove the error-highlight class if the input is valid
            $(this).removeClass("error-highlight");
        } else {
            // Clear the input field, add the error-highlight class, and display an error message
            $(this).val(""); // Clear the input field
            $(this).addClass("error-highlight");

            // Display an error message (you can customize this message)
            alert("Special characters and numbers are not allowed.");
        }
    });
}

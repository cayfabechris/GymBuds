function validateLogin(){
    var email = document.forms["login"]["email"].value.trim();
    var password = document.forms["login"]["password"].value;

    if(email == "" && password == ""){
        console.log("User entered left email and password blank.")
        alert("Please enter a valid email and password");
        return false;
    }

    else if(email == "" && password != ""){
        console.log("User entered left email blank.")
        alert("Please enter a valid email");
        return false;
    }

    else if(email != "" && password == ""){
        console.log("User entered left password blank.")
        alert("Please enter a valid password");
        return false;
    }
}
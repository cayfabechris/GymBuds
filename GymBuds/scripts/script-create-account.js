function myFunction() {
    var first_name = document.forms["create-account"]["first-name"].value.trim();
    var last_name = document.forms["create-account"]["last-name"].value.trim();
    var username = document.forms["create-account"]["username"].value.trim();
    var email = document.forms["create-account"]["email"].value.trim();
    var password = document.forms["create-account"]["password"].value.trim();

    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
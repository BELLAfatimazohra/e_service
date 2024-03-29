document.addEventListener("DOMContentLoaded", function () {
  // Your JavaScript code here
  const show = document.getElementById("showPwd");
  const password = document.getElementById("password");
  const nonVisible = document.querySelector(".pwd");
  const visible = document.querySelector(".pwdv");

  show.addEventListener("change", () => {
    if (show.checked) {
      password.setAttribute("type", "text");
      visible.style.display = "block";
      nonVisible.style.display = "none";
    } else {
      password.setAttribute("type", "password");
      nonVisible.style.display = "block";
      visible.style.display = "none";
    }
  });

  password.addEventListener("focus", () => {
    visible.style.opacity = nonVisible.style.opacity = "0.9";
  });

  password.addEventListener("blur", () => {
    visible.style.opacity = nonVisible.style.opacity = "0.3"; // Or any value you want
  });


});

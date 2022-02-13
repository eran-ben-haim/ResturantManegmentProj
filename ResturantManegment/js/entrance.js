"use strict";
// const entranceCahier = document.querySelector("#entranceCahier");
const containerwrongpassword = document.querySelector(
  ".containerwrongpassword"
);
const closeForm = document.querySelector("#goBackToInsertPassword");
// entranceCahier.addEventListener("click", function () {
//   containerwrongpassword.style.display = "block";
// });
goBackToInsertPassword.addEventListener("click", function () {
  containerwrongpassword.style.display = "none";
});

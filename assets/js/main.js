import { DataManager } from "./script.js";

const UserControl = new DataManager;


const deleteButtons = document.querySelectorAll(".table-deleteBtn");

// add event listener for every delete button
deleteButtons.forEach(function (button) {
     button.addEventListener("click", () => {
          const trId = button.closest("tr").id;
          UserControl.deleteUser(trId);
     });
});
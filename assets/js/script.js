export class DataManager {

     // find user key "id" and send it to php method
     deleteUser(userId) {
          let isDeleteConfirm = window.confirm("Are you sure to permanently remove this user?");
          if (isDeleteConfirm === true) {

               userId = userId.replace("user", "");
               console.log(userId);
               fetch('./userId.php', {
                    method: 'POST',
                    headers: {
                         'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'user_id=' + encodeURIComponent(userId)
               })
                    .then(response => response.text())
                    .catch(error => {
                         console.error('Error:', error);
                    });

               // reload page
               location.reload();
          }
     }

}

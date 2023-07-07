<?php

//---------------------------------
// model
//---------------------------------

class UserModel
{
    private $controller;

    public function __construct(UserController $controller)
    {
        $this->controller = $controller;
    }

    public function getUsers()
    {
        return $this->controller->getUsers();
    }

    public function formatAddress($address)
    {
        return $this->controller->formatAddress($address);
    }

    public function displayUsersTable()
    {
        $users = $this->getUsers();

        $html = '<table>
               <tr>
                 <th>Name</th>
                 <th>Username</th>
                 <th>Email</th>
                 <th>Address</th>
                 <th>Phone</th>
                 <th>Company</th>
                 <th></th>
              </tr>';

        foreach ($users as $user) {
            $html .= '<tr' . ' id="user' . $user['id'] . '">';
            $html .= '<td>' . $user['name'] . '</td>';
            $html .= '<td>' . $user['username'] . '</td>';
            $html .= '<td>' . $user['email'] . '</td>';
            $html .= '<td>' . $this->formatAddress($user['address']) . '</td>';
            $html .= '<td>' . $user['phone'] . '</td>';
            $html .= '<td>' . $user['company']['name'] . '</td>';
            $html .= '<td>' . '<button type="button" class="table-deleteBtn" id="' . $user['id'] . '">Remove</button>' . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }
}



//---------------------------------
// controlers
//---------------------------------

class UserController
{
    public function getUsers()
    {
        $usersData = file_get_contents('./dataset/users.json');

        // convert table
        $users = json_decode($usersData, true);

        // check is get data succesful
        if ($users === null) {
            echo "Error: get data failed.";
            exit;
        }

        return $users;
    }



    public function formatAddress($address)
    {
        $formattedAddress = $address['street'] . ', ' . $address['suite'] . ', '
            . $address['city'] . ', ' . $address['zipcode'];
        return $formattedAddress;
    }

    // listen for post method from add user form
    public function receiveDataUserFromForm()
    {
        if (isset($_POST)) {
            $message = $this->validFormData(
                $_POST['name'], $_POST['username'], $_POST['email'], $_POST['street'], $_POST['suite'],
                $_POST['city'], $_POST['zipcode'], $_POST['phone'], $_POST['company']
            );
        } else {
            $message = "Error: valid data failed.";
        }
        echo '<div class="formError">';
        echo "message:" . $message;
        echo '</div>';
        if($message == "Validation success.") {
            $this->reloadPage();
        } else {
            echo "Error: validation failed.";
            $this->reloadPage();
        }
    }

    // listen data from user remove button from javascript
    public function listenDataToDelete()
    {
        if (isset($_POST)) {
            if (isset($_POST['user_id'])) {
                $userId = $_POST['user_id'];
                $response = "ID: " . $userId;

                $responseData = array('message' => $response);
                header('Content-Type: application/json');
                echo json_encode($responseData);
                $this->deleteUser($userId);
            } else {
                $response = "Error: no received data";
            }
        }
    }

    public function validFormData($name, $username, $email, $street, $suite, $city, $zipcode, $phone, $company)
    {

        $errors = [];

        if (strlen($name) < 4 || strlen($name) > 30) {
            $errors[] = "Name must be between 4 and 30 characters.";
        }

        if (empty($username)) {
            $errors[] = "Username is required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }

        if (empty($street)) {
            $errors[] = "Street is required.";
        }

        if (empty($suite)) {
            $errors[] = "Suite is required.";
        }

        if (empty($city)) {
            $errors[] = "City is required.";
        }

        if (empty($zipcode)) {
            $errors[] = "Zipcode is required.";
            if (!preg_match('/^[0-9]{5}$/', $zipcode)) {
                $errors[] = "Invalid zipcode format. Zipcode must be a 5-digit number.";
            }
        }

        if (empty($phone)) {
            $errors[] = "Phone is required. <br/>";
        }
        if (!preg_match('/^[0-9]{9}$/', $phone)) {
            $errors[] = "Invalid phone number. Phone number must be a 9-digit number. <br/>";
        }
        if (empty($company)) {
            $errors[] = "Company is required. <br/>";
        }

        // add user if no errors
        if (!empty($errors)) {
            $_SESSION['form_error'] = $errors;

            return "Validation failed.";
        } else {
            $this->addUser(
                $name,
                $username,
                $email,
                $street,
                $suite,
                $city,
                $zipcode,
                $phone,
                $company
            );

            return "Validation success.";
        }
    }


    // remove user object from json file
    public function deleteUser($userId)
    {
        $file = './dataset/users.json';

        // find data
        $data = file_get_contents($file);
        $users = json_decode($data, true);

        // find user to removed by id and remove
        foreach ($users as $key => $user) {
            if ($user['id'] == $userId) {
                unset($users[$key]);
                break;
            }
        }

        // format and save file
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
    }

    // add new user to json file
    private function addUser($name, $username, $email, $street, $suite, $city, $zipcode, $phone, $company)
    {
        $file = './dataset/users.json';

        // read data from file
        $data = file_get_contents($file);
        $users = json_decode($data, true);

        // find highest id in file
        $maxId = 0;
        foreach ($users as $user) {
            if ($user['id'] > $maxId) {
                $maxId = $user['id'];
            }
        }

        // generate id for new user
        $newId = $maxId + 1;

        // create object with new user data
        $newUser = [
            "id" => $newId,
            "name" => $name,
            "username" => $username,
            "email" => $email,
            "address" => [
                "street" => $street,
                "suite" => $suite,
                "city" => $city,
                "zipcode" => $zipcode,
                "geo" => [
                    "lat" => "",
                    "lng" => ""
                ]
            ],
            "phone" => $phone,
            "website" => "",
            "company" => [
                "name" => $company,
                "catchPhrase" => "",
                "bs" => ""
            ]
        ];

        // add object to table
        $users[] = $newUser;

        // format data and save modified table to json file
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

        // reload the page to print updated table
        $this->reloadPage();
    }

    private function reloadPage()
    {
        echo '<script>window.location.href = "' . $_SERVER['REQUEST_URI'] . '";</script>';
    }

}
?>
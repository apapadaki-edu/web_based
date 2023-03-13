<?php require_once('config.php');?>
<?php

//General class state which has two functions one to 
//Communicate with the data base and one to flash the result html


abstract class State {
    abstract function getHtml($arg); //does not return anything
    abstract function execDB($arg); //returns the result of the query
}

class DeleteCustomer extends State {

    public function getHtml($id){
        header('Location:administration.php?id='.$id);
    }

    public function execDB($id){ //remember to pass $_GET['id'] instead of $id
        try{
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = "DELETE FROM customer WHERE id= :id";
            
            $stmt = $pdo->prepare($stmt);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT); //$_GET['id'] instead of $id if not in function
            $stmt->execute();  
            $stmt = null;
            return $id;
        
        }catch(PDOException $e) {
            throw new Exception ("Error: " . $e->getMessage());
        }
    }
}

class ViewCustomer extends State {

    public function getHtml($row){

        echo "<table><caption style=\"background-color:rgb(66, 139, 202);color:white;font-size:1.15rem;padding:3%\">Personal Information</caption>";
        echo   "<tr><td>First Name</td><td>".$row[0]['first_name']."</td></tr>";
        echo   "<tr><td>Last Name</td><td>".$row[0]['last_name']."</td></tr>";
        echo   "<tr><td>Email</td><td>".$row[0]['email']."</td></tr>";
        echo   "<tr><td>Birth Day</td><td>".$row[0]['birth_date']."</td></tr>";
        echo   "<tr><td>Phone</td><td>". $row[0]['phone']."</td></tr>";
        echo   "<tr><td>Address</td><td>".$row[0]['address']."</td></tr>";
        echo   "<tr><td>City</td><td>". $row[0]['city']."</td></tr>";
        echo   "<tr><td>State</td><td>".$row[0]['state']."</td></tr>";
        echo   "<tr><td>Zip</td><td>".$row[0]['zip']."</td></tr>";
        echo   "<tr><td>Country</td><td>".$row[0]['country']."</td></tr>";
        echo   "<tr><td colspan=\"2\"><a href=\"administration.php\"><button>List Costumers</td></button></a></tr>";
        echo "</table>";

    }

    public function execDB($id){ //remember to pass $_GET['id'] instead of $id
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = "SELECT * FROM customer WHERE id= :id";
            $stmt = $pdo->prepare($stmt);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);  //$_GET['id'] instead of $id if not in function
            $stmt->execute();
            $row = $stmt->fetchAll();  
            $stmt = null;
            return $row;

        }catch(PDOException $e) {
            throw new Exception ("Error: " . $e->getMessage());
        }
    }
}

class ListCustomer extends State {

    public function getHtml($result){
        echo "<table><caption style=\"background-color:rgb(66, 139, 202);color:white;font-size:1.15rem;padding:2.5%\">Customers</caption>";
        echo "<tr><th>Name</th><th>Email</th> <th>City</th><th>Country</th> </tr>";
        while ($row = $result->fetch()) {
            echo "<td>".$row['first_name']." ".$row['last_name']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>".$row['city']."</td>";
            echo "<td>".$row['country']."</td>";
            echo '<td> <a href="administration.php?id=' . $row['id'] . '&status=view" '.'"> <span style="padding:0.3em 0.5em;margin-right:0.3em">View</span></a>';
            echo '<a href="administration.php?id=' . $row['id'] . '&status=edit" '.'"> <span style="padding:0.3em 0.5em;margin-right:0.3em">Edit</span></a>';
            echo '<a href="administration.php?id=' . $row['id'] . '&status=delete" '.'"> <span style="padding:0.3em 0.5em;margin-right:0.3em"> Delete</span></a> </td></tr>';
        }
        echo "</table>";

    }

    public function execDB($id=null){ //remember to pass $_GET['id'] instead of $id
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = "SELECT * FROM customer ORDER BY id";
            $result = $pdo->query($stmt);
            $stmt = null;
            return $result;

        }catch(PDOException $e) {
            throw new Exception ("Error: " . $e->getMessage());
        }
    }
}

class UpdateCustomer extends State {

    public function getHtml($result){
        echo "<h2 style=\"background-color:#c0c0c0;font-size:1.15rem;padding:3%\">Update Status</h2>";
        echo '<p style="margin:1.5rem 1rem 1rem 1rem">'.$result[0]." ".$result[1]." have been successfully updated ";
        echo " <a href=\"administration.php?status=none\">Back</a></p>";

    }

    public function execDB($user){ //remember to pass $_GET
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $stmt = $pdo->prepare("UPDATE customer SET first_name=:fname, 
            last_name=:lname, birth_date=:birthday, email=:email, 
            phone=:phone, address=:address, city=:city, state=:state, 
            zip=:zip, country=:country WHERE id=:id");

            $stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
            $stmt->bindParam(':fname', $user['fname']);
            $stmt->bindParam(':lname', $user['lname']);
            $stmt->bindParam(':birthday', $user['birthday']);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':phone', $user['phone']);
            $stmt->bindParam(':address', $user['address']);
            $stmt->bindParam(':city', $user['city']);
            $stmt->bindParam(':state', $user['state']);
            $stmt->bindParam(':zip', $user['zip']);
            $stmt->bindParam(':country', $user['country']);

            $stmt->execute();
            $stmt = null;
            return [$user["fname"], $user["lname"]];

        }catch(PDOException $e) {
            throw new Exception ("Error: " . $e->getMessage());
        }
    }
}

class InsertCustomer extends State {
    public function getHtml($result){
        echo "<h2 style=\"background-color:rgb(66, 139, 202);color:white;font-size:1.15rem;padding:3%\">Update Status</h2>";
        echo '<p style="margin:1.5rem 1rem 1rem 1rem">'.$result['lname']." ".$result['fname']." your personal have been saved successfully!";        
        echo '<a href="index.php" style="background-color:rgb(66, 139, 202);color:white;padding:0.6rem;margin-left:1rem">New Customer</a> ';
        echo '<a href="administration.php" style="background-color:rgb(66, 139, 202);color:white;padding:0.6rem">List Customers</a></p>'; 
    }

    public function execDB($user){ //remember to pass $_POST
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare("INSERT INTO customer (first_name, last_name, birth_date, email, phone, address, city, state, zip, country)
                VALUES (:fname, :lname, :birthday, :email, :phone, :address, :city, :state, :zip, :country)");
            $stmt->bindParam(':fname', $user['fname']);
            $stmt->bindParam(':lname', $user['lname']);
            $stmt->bindParam(':birthday', $user['birthday']);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':phone', $user['phone']);
            $stmt->bindParam(':address', $user['address']);
            $stmt->bindParam(':city', $user['city']);
            $stmt->bindParam(':state', $user['state']);
            $stmt->bindParam(':zip', $user['zip']);
            $stmt->bindParam(':country', $user['country']);
            $stmt->execute();
            $stmt = null;
            return $user;

        }catch(PDOException $e) {
            throw new Exception ("Error: " . $e->getMessage());
        }

    }
}

class EditCustomer extends State {

    public function getHtml($user){
        echo '<form "method="GET" action="administration.php?id='.$user['id'].'&status=update">
        <fieldset id="account">
            <legend>Account</legend>
            <div class="fieldItem">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="'.$user['email'].'" placeeholder="email@example.com" >
            </div>
            <div class="fieldItem">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="At least 8 characters" minlength="8" disabled>
            </div>
            <div class="fieldItem">
                <label for="rpassword">Repeat Password</label>
                <input type="password" id="rpassword" name="rpassword" placeholder="At least 8 characters" minlength="8" disabled>
            </div>
        </fieldset>
        <fieldset id="profile">
            <legend>Profile</legend>
            <div class="fieldItem">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" value="'.$user['first_name'].'" pattern="^[A-Za-z][A-Za-z\.\- ]+[A-Za-z]$">
            </div>
            <div class="fieldItem">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" value="'.$user['last_name'].'" pattern="^[A-Za-z][A-Za-z\.\- ]+[A-Za-z]$">
            </div>
            <div class="fieldItem">
                <label for="birthday">Birth Day</label>
                <input type="date" id="birthday" name="birthday" value="'.$user['birth_date'].'">
            </div>
        </fieldset>
        <fieldset id="contact">
            <legend>Contact</legend>
            <div class="fieldItem">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="'.$user['address'].'">
            </div>
            <div class="fieldItem">
                <label for="state">State/Region</label>
                <input type="text" id="stat" name="state" value="'.$user['state'].'">
            </div>
            <div class="fieldItem">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="'.$user['city'].'" pattern="^[A-Za-z][A-Za-z]+[A-Za-z]$">
            </div>
            <div class="fieldItem">
                <label for="zip">Zip</label>
                <input type="text" id="zip" name="zip" value="'.$user['zip'].'" max="8" pattern="^\d[\d ]{3}\d$">
            </div>
            <div class="fieldItem">
                <label for="country">Country</label>';
                listCountries('countries.txt', $user['country']);
            echo '</div>
            <div class="fieldItem">
                <label for="mobile">Phone</label>
                <input type="tel" id="phone" name="phone"value="'.$user['phone'].'" placeholder="NNN-NNN-NNNN" pattern="^\d{3}\-\d{3}\-\d{4}$" required>
            </div>
        </fieldset>
        <div id=termsField>
            <input type="checkbox" id="terms" name="terms" value="1" checked>
            <label for="terms">I agree to the <a href="#">Terms of the Site</a></label>
        </div>
        <button type="submit" value="submit">Update</button>
        <input type="hidden" name="status" value="update">
        <input type="hidden" name="id" value="'.$user['id'].'">
        </form>';

    }

    public function execDB($id){ //remember to pass $_POST
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
            $stmt = "SELECT * FROM customer WHERE id= :id";
            $stmt = $pdo->prepare($stmt);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetchAll();
            $stmt = null;
            return $row[0];
        }catch(PDOException $e) {
            throw new Exception ("Error: " . $e->getMessage());
        }
    }
}
?>


<?php
function listCountries($countryFile, $contryName){
    echo '<select id="country" name="country">';
    $file_cont = file_get_contents($countryFile);
    $countries = explode("\n", $file_cont);
    foreach ($countries as $country){
        if (empty($country)) continue;
        if ($country == $contryName) {
            echo "<option value=\"$country\" selected=\"1\">$country</option>";
        }
        else {
            echo "<option value=\"$country\">$country</option>";
        }
    }
    echo '</select>';
}
?>
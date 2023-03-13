<?php require_once("states.php");?>
<?php
    $state = null;
    $queryARG = null;
    try{
        if (!isset($_GET['status']) || $_GET['status']=='none'|| $_SERVER['method']=='POST'){
            if ($_POST['status']=='insert'){
                $state = new InsertCustomer();
                $queryARG = $_POST;
            }else{
                $state = new ListCustomer();
            }
        } else {
            if (!isset($_GET['id']) ) {           
                echo  "Please choose a <a href='administration.php'> Customer </a> first </body></html>"; 
                exit;
            }
            switch ($_GET['status']){
                case 'view':
                    $state = new ViewCustomer();
                    $queryARG = $_GET['id'];
                    break;
                case 'update':
                    $state = new UpdateCustomer();
                    $queryARG = $_GET;
                    break;
                case 'delete':
                    $state = new DeleteCustomer();
                    $queryARG = $_GET['id'];
                    $state->getHtml($queryARG);
                    break;
                case 'edit':
                    $state = new EditCustomer();
                    $queryARG = $_GET['id'];
                    break;
            }
        }
        // execute the query and return the records if any   
        $queryResult = $state->execDB($queryARG); 

    }catch(Exception $e){    
        echo "Error: " . $e->getMessage();
    }
?> 

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/be3ff5ad85.js" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0px 0px;
        }
        
        @media (max-width: 979px) {
            .main-container {
                display: grid;
                grid-template-areas: 'main' 'similar' 'aside';
                grid-gap: 20px;
                padding: 10px;
            }

            .container {
                display: contents;  /*remove unwanted space (avoid horizontal scrolling/overflow)*/           
            }
        }

        @media (min-width: 980px) {
            .main-container {
                display: grid;
                grid-template-columns: repeat(5, 1fr) 1fr;
                grid-template-areas: 'main main main main main aside''similar similar similar similar similar aside';
                grid-gap: 20px;
            }

            .container {
                max-width: 1300px;
                margin: auto;
            }
        }
        
        .grid-container {
            display: grid;
            /* κάθε γραμμή του πλέγματος περικλείεται σε αποστρόφους */
            grid-template-areas: 'header header header header header header' 'main main main main main main' 'footer footer footer footer footer footer';
            grid-gap: 3% 10px;
        }
        
        .item1 {
            grid-area: header;
            /* αντιστοιχεί ονόματα στα items του grid*/
        }
        
        .item2 {
            grid-area: main;
        }
        
        .item3 {
            grid-area: footer;
            background-color: rgb(231, 231, 231);
        }
        
        @media (max-width: 700px) {
            .main-item1 {
                grid-area: main;
                display: grid;
                grid-row-gap: 1rem;
                grid-template-areas: 
                    "portrait-name"
                    "artist"
                    "portrait" 
                    "details";
                /* αντιστοιχεί ονόματα στα items του grid*/
            }
        }

        @media (min-width: 701px) {
            .main-item1 {
                grid-area: main;
                display: grid;
                grid-auto-rows: min-content;
                justify-content: start;
                gap: 1rem 2rem;
                grid-template-columns: 1fr 2fr;
                grid-template-areas: 
                    "portrait-name portrait-name"
                    "artist artist"
                    "portrait details";
                /* αντιστοιχεί ονόματα στα items του grid*/
            }

            .container {
                max-width: 1300px;
                margin: auto;
                /* γιατί υπερχείλιση περιεχομένου στο main??????
                border: solid 1px#0963ae; */
            }
        }
        
        
        .main-item2 {
            grid-area: similar;
            /* αντιστοιχεί ονόματα στα items του grid*/
        }

        .main-item3 {
            grid-area: aside;
            display:flex;
            flex-direction: column;
            justify-content: flex-start;
            align-self: stretch;
            flex-wrap: nowrap;
            row-gap: 2rem;
        }
        
        @media (max-width: 700px) {
            .footer-container {
                display: grid;
                grid-template-areas: 'footer1' 'footer2' 'footer3' 'footer4' 'copy';
                grid-gap: 5px;
            }
        }

        @media (min-width: 701px) {
            .footer-container {
                display: grid;
                grid-column-gap: 2rem;
                grid-template-columns: repeat(4, 1fr);
                grid-template-areas: 'footer1 footer2 footer3 footer4''copy copy copy copy';
                margin-top: 1em;
            }
        }
        
        .footer-item1 {
            grid-area: footer1;
            /* αντιστοιχεί ονόματα στα items του grid*/
        }
        
        .footer-item2 {
            grid-area: footer2;
            /* αντιστοιχεί ονόματα στα items του grid*/
        }
        
        .footer-item3 {
            grid-area: footer3;
        }

        .footer-item4 {
            grid-area: footer4;
        }
        
        .footer-item5 {
            grid-area: copy;
        }
    </style>
    <link href="css/all.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <div class="grid-container">

        <header class="item1">
            <div class="container"> 
                <div id="topHeaderRow">
                    <p>Welcome to <a href="#">Art Store</a>, <a href="#">Login</a> or <a href="#"> Create new account</a>
                    <nav>
                        <ul>
                            <li><i class="fas fa-user"></i><a href="#"> My Account</a></li>
                            <li><a href="#cartInfo" class="responsive-active-only"><i class="fas fa-shopping-cart"></i></a><a href="#"> Shopping Cart</a></li>
                            <li><i class="fas fa-gift"></i><a href="#"> Wish List</a></li>
                            <li><i class="fas fa-arrow-right"></i><a href="#"> Checkout</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- end topHeaderRow -->
        
                <div id="logoRow">
                    <h1>Art Store</h1>
        
                    <form>
                        <label for="search">Search</label>
                        <input type="text" placeholder="Search" name="search">
                        <button type="submit"><span class="fas fa-search"></span></button>
                    </form>
                </div>
                <!-- end logoRow -->
        
                <div id="mainNavigationRow">
                    <a href="#navigationMain" class="responsive-effects not-in-tablet"><i class="fas fa-bars"></i></a>
                    <nav id="navigationMain">
                        <a href="#" class="responsive-effects not-in-tablet"><i class="far fa-window-close"></i></a>
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Art Works</a></li>
                            <li><a href="#">Artists</a></li>
                            <li><a href="#">Specials </a></li>
                        </ul>
        
                    </nav>
                </div>
                <!-- end mainNavigationRow --> 
            </div>
        </header>



        <div class="item2">
            <div class="container">
                <div class="main-container">
                    <main class="main-item1">
                        <h2>Customer Administration</h2>

                        <picture>
                            <source media="screen and (max-width:900px)" srcset="images/113010-m.jpg">
                            <img src="images/113010-m.jpg" class="img-thumbnail img-responsive" alt="Self-portrait in a Straw Hat" />
                        </picture>

                        <section id="details">
                            <!-- Wrap the element that changes between pages (i.e. table - form) in a container
                            for placement in the grid template. -->
                            <div id="insertedBox">
                                <?php  $html = $state->getHtml($queryResult); ?>
                            </div>
                            <!-- end insertedBox -->
                        </section>
                        <!-- end details -->
                    </main>

                    <aside class="main-item3">
                        <div id="cartInfo">
                            <h3>Cart <a tabindex= "1" href="#" class="responsive-effects close"></a></h3> 
                            <div class= "cart-container">
                                <div class="cartItem">
                                    <a href="#">
                                        <img src="images/tiny/116010.jpg" alt="..." width="32">
                                    </a>
                                    <p><a href="#">Artist Holding a Thistle</a></p>
                                </div>
                                <div class="cartItem">
                                    <a href="#">
                                        <img src="images/tiny/113010.jpg" alt="..." width="32">
                                    </a>
                                    <p><a href="#">Self-portrait in a Straw Hat</a></p>
                                </div>
                                
                                <strong class="cartText">Subtotal: <span class="totalAmount">$1200</span></strong>
                                <div>
                                    <button type="button"><i class="fas fa-info-circle"></i> Edit</button>
                                    <button type="button"> <i class="fas fa-arrow-right"></i> Checkout</button>
                                </div>
                            </div>
                        </div>

                        <div class="popularCategory">
                            <h3>Popular Artists</h3>
                            <ul>
                                <li>
                                    <img src="images/artists/101.jpg" class="responsive-effects" title="Caravaggio" alt="Caravaggion">
                                    <a href="#">Caravaggio</a>
                                </li>
                                <li>
                                    <img src="images/artists/20.jpg" class="responsive-effects" title="Cezanne" alt="Cezanne">
                                    <a href="#">Cezanne</a>
                                </li>
                                <li>
                                    <img src="images/artists/23.jpg" class="responsive-effects" title="Matisse" alt="Matisse">
                                    <a href="#">Matisse</a>
                                </li>
                                <li>
                                    <img src="images/artists/98.jpg" class="responsive-effects" title="Michelangelo" alt="Michelangelo">
                                    <a href="#">Michelangelo</a>
                                </li>
                                <li>
                                    <img src="images/artists/22.jpg" class="responsive-effects" title="Picasso" alt="Picasso">
                                    <a href="#">Picasso</a>
                                </li>
                                <li>
                                    <img src="images/artists/99.jpg" class="responsive-effects" title="Raphael" alt="Raphael">
                                    <a href="#">Raphael</a>
                                </li>
                                <li>
                                    <img src="images/artists/19.jpg" class="responsive-effects" title="Van Gogh" alt="Van Gogh">
                                    <a href="#">Van Gogh</a>
                                </li>
                            </ul>
                        </div>
                        <div class="popularCategory">
                            <h3>Popular Genres</h3>
                            <ul>
                                <li><a href="#">Baroque</a></li>
                                <li><a href="#">Cubism</a></li>
                                <li><a href="#">Impressionism</a></li>
                                <li><a href="#">Renaissance</a></li>
                            </ul>
                        </div>
                    </aside>
                </div>

            </div>
        </div>


        <footer class="item3"> 
            <div class="container">
                <div class="footer-container">
                    <div class="footer-item1">
                        <h4><i class="fas fa-info-circle"></i> About Us</h4>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>            
                    </div>

                    <div class="footer-item2">
                        <h4><i class="fas fa-phone-alt"></i> Customer Service</h4>
                        <ul>
                            <li><a href="#">Delivery Information</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Shipping</a></li>
                            <li><a href="#">Terms and Conditions</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-item3">
                        <h4><i class="fas fa-shopping-cart"></i> Just Ordered</h4>
                        <div>
                            <a href="#">
                                <img src="images/tiny/13030.jpg" alt="Arrangement in Grey and Black">
                            </a>
                            <p> <a href="#">Arrangement in Grey and Black</a></p>
                            <em>5 minutes ago</em>
                        </div>
                        <div>
                            <a href="#">
                                <img src="images/tiny/116010.jpg" alt="Artist Holding a Thistle">
                            </a>
                            <p><a href="#">Artist Holding a Thistle</a></p>
                            <em>11 minutes ago</em>
                        </div>
                        <div>
                            <a href="#">
                                <img src="images/tiny/113010.jpg" alt="Self-portrait in a Straw Hat">
                            </a>
                            <p><a href="#">Self-portrait in a Straw Hat</a></p>
                            <em>23 minutes ago</em>
                        </div>
                    </div>
                    
                    <div class="footer-item4" id="form-disappear">
                        <h4><i class="fas fa-envelope"></i> Contact us <a href="#contact-form-appear" class="responsive-effects  not-in-tablet"><i class="fas fa-caret-square-up"></i></a></h4>
                        <fieldset id="contact-form-appear">  
                            <legend class="responsive-effects">Contact</legend> 
                            <form> <a tabindex= "1" href="#form-disappear" class="close  not-in-tablet"></a>
                                <div class="field">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" class="input-field" name="name" placeholder="Enter Name ..." pattern="[A-Za-z\.\- ]+[A-Za-z]$" title="only a-z A-Z . -">
                                </div>
                                <div class="field">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="input-field" name="email" placeholder="Enter email ...">
                                </div>
                                <div class="field">
                                    <label for="message">Message</label>
                                    <textarea id="message" class="input-field" name="message" rows="4" maxlength="1000" placeholder="Enter message ..." pattern="^[\w. ]{0,1000}+[\w.]$"></textarea>
                                </div>
                                <button type="submit">Submit</button>
                            </form>
                        </fieldset>
                    </div>

                    <div class="footer-item5">
                        <p>All images are copyright to their owners. This is just a hypothetical site
                            <span>&copy; 2021 Copyright Art Store</span></p>
                    </div>
                </div>
            </div>
        </footer>

    </div>

</body>

</html>
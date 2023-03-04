<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>View Project</title>
    <link rel="stylesheet" type="text/css" href="assets/css/project_view.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v6.3.0/css/all.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body id="page-top">

    <?php

        // start a session
        session_start();

        // check if the user is logged in
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        }

        // establish db connection
        require("db.php");

        // import helper methods
        require("helper.php");

        // retrieve user details
        $user_details = get_user_details($_SESSION["user_id"], "user_id");

        // ensure that the get request is valid
        if (isset($_GET["project_id"])) {

            // collect project id
            $project_id = $_GET["project_id"];

            // retrieve project details
            $project_details = get_project_details($project_id, "project_id");

            if ($project_details == -1) {
                header("Location: user_dashboard.php");
                exit();
            } 
        } else {
            header("Location: user_dashboard.php");
            exit();
        }
    ?>

    <div id="wrapper">
        <div style="background: rgba(235, 174, 142, 1); min-height: 100vh;">
            <div id="nav-contain" style=" padding: 2em;">

                <div style="height: 200px; margin: auto; width: 200px">
                    <?php if ($user_details["profile_image"] == NULL) {
                        echo '<img id = "profile" class="rounded-circle mb-3 mt-4" src="/WebTech_TeamProject/WebGeeks/assets/img/default_profile.png" width="160" height="160" style="margin-left: -68px;">';
                    } else {
                        echo '<img id = "profile" class="rounded-circle mb-10 mt-4" src="/WebTech_TeamProject/WebGeeks/assets/images/profile_images/' . $user_details["user_id"]  . '-' . $user_details["profile_image"] . '"' .
                        'width="160" height="80" style="margin-left: -68px;">';
                    }
                    ?>
                </div>

                <div>
                    <h3 style="color: black; padding: 10px 5px;"><?php echo $user_details["firstname"] . " " . $user_details["lastname"] ?></h3>
                </div>
                

                <ul class="navbar-nav text-light" id="accordionSidebar">
                        <li class="nav-item">
                            <a class="nav-link active" id="link1" href="user_dashboard.php">
                                <img src="assets/img/Project%20Management.png" width="30" height="22" style="width: 31px;height: 31px;">
                                <span style="margin: 4px;color: var(--bs-black);">Projects</span>
                            </a>
                            <div style="padding-left: 2em;">
                                <a class="nav-link active" id="link-3" href="your_projects.php">
                                    <img src="assets/img/Training.png" width="26" height="27">
                                    <span style="margin: 4px;color: var(--bs-black);">Your Projects</span>
                                </a>
                                <a class="nav-link active" id="link-2" href="requested_projects.php">
                                    <img src="assets/img/How%20Quest.png" width="31" height="24" style="height: 31px;">
                                    <span style="margin: 4px;color: var(--bs-black);">Request Projects</span>
                                </a>
                                <a class="nav-link active" id="link-1" href="invited_projects.php">
                                    <img src="assets/img/Send%20Hot%20List.png" width="21" height="21" style="width: 31px;height: 31px;">
                                    <span style="margin: 4px;color: var(--bs-black);">Invited Projects</span>
                                </a>
                            </div>
                            <a class="nav-link active" id="link-1" href="trash_page.php">
                                <img src="https://img.icons8.com/ios/50/null/empty-trash.png" width="21" height="21" style="width: 31px;height: 31px;">
                                <span style="margin: 4px;color: var(--bs-black);">Trash</span>
                            </a>
                            <a id="logout-btn" class="btn btn-primary ms-lg-3"  href="logout.php" role="button">Log out
                                <img src="https://img.icons8.com/ios-glyphs/30/null/logout-rounded-left.png"height="23px"/>
                            </a>
                        </li>
                    </ul>
                <div class="text-center d-none d-md-inline"></div>
            </div>
        </div>

        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav id = "mybar" class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="icons-house">
                        <div class="eye" id="eye1"><p class="visibility-text1">Member Acquisition:</p>
                            <div class="visibility"><i id = "lock" class="fa-sharp fa-solid fa-lock"></i></div>
                    </div>
                        <div class="eye"><p class="visibility-text">Project Visibility:</p><div class="visibility"><i id = "eye-slash" class="fa-solid fa-eye"></i></div></div>
                        <div class="eye">
                            <div class="visibility2" onclick="showform()">
                                <i style="width:20px; text-align:center;"id = "visibility2" class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                <span class="tooltiptext">
                                    <ul style="list-style-type: none;">
                                        <li id="add-member">Add new members</li>
                                        <li id="invite-member">Invite members</li>
                                    </ul>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                </nav>
                

                <div class="container-fluid">
                <h3 class="description">Your projects</h3>
                    <div class="content">

                        <div class="left">
                            <div style="min-height: 250px; min-width: 320px">
                                <?php 
                                    echo '<img width=100% height=300px src="/WebTech_TeamProject/images/project_images/' . 
                                    $project_details["project_name"]. "-" . $project_details["project_image"] . '">';
                                ?>
                            </div>

                            <div class="members">
                                <div class="member-item" id="member-title">
                                    <div class="member-name"> Member </div>
                                    <div id="member-role">
                                        Role
                                    </div>

                                </div>
                                
                                <div class="member-item">
                                    <?php 

                                        // retrieve all project members
                                        $project_members = get_project_members($project_id);

                                        // loop through the members and echo their roles
                                        if ($project_members == -1) {
                                            echo "Failed to load project members.";
                                        } else {
                                            // project_member attributes and corresponding indexes
                                            $USER_ID_INDEX = 0;
                                            $PROJECT_ID_INDEX = 1;
                                            $ROLE_ID_INDEX = 2;
                                            
                                            foreach ($project_members as $project_member) {
                                                // retrieve details of the member
                                                $member_details = get_user_details($project_member[$USER_ID_INDEX], "user_id");

                                                // retrieve the role details
                                                $role_details = get_role_details($project_member[$ROLE_ID_INDEX]);

                                                echo '<div class="member-name">' . $member_details["firstname"] . ' ' . $member_details["lastname"] . '</div>
                                                    <div class="member-role">' . $role_details["role_name"] . '</div>';
                                            }
                                        }

                                    ?>
                                </div>
                                
                            </div>
                            
                            <div class="container2">
                                <form id="form" action="<?php echo $_SESSION["invite_url"] ?>" method="POST">
                                    <div><span class="close">&times;</span></div>
                                    
                                    <br><br>

                                    <label for="">Email:</label>
                                    <input type="text" name="email" id="email">
                                    
                                    <br><br>

                                    <label style="display:inline-flex;"for="">Role:</label>
                                    <select style="width:70%; height:17%; display:inline-flex;" name="role" id="role" required>
                                    
                                        <?php 
                                            // retrieve user defined roles
                                            $user_roles = get_user_defined_roles($_SESSION["user_id"]);

                                            // user_roles attributes and corresponding indexes
                                            $USER_ID_INDEX = 0;
                                            $ROLE_ID_INDEX = 1;

                                            // echo returned results
                                            echo '<option disabled selected value> -- select an option -- </option>';

                                            foreach ($user_roles as $role) {
                                                // retrieve role details
                                                $role_details = get_role_details($role[$ROLE_ID_INDEX]);

                                                echo '<option value="' . $role_details["role_id"] . '">' . $role_details["role_name"] . ' </option></option>';
                                            }
                                        
                                        ?>
                                    </select>
                                    <a href="create_role.php" style="text-decoration: none; color: black">
                                        <i style="display:inline;font-size:20px; margin-left:3px;"class="fa-regular fa-plus add-button" id = 'add-buttons' ></i>
                                    </a>
                                    <br><br>

                                    <button id ="send" type="submit" name="invite-member">Invite</button>
                                </form>
                            </div>
                            
                            <div class="button">
                                <button id="button-text" onclick="showinput()">Invite Members</button>
                                <!-- <div id="invitation">
                                    <div class="send-buttons">
                                        <input style="border:none; border-bottom:2px solid crimson" placeholder="Email"type="text" id="text-entry">
                                    </div>
                                    <div class="send-buttons2">
                                        <span style="font-size:22px"; id="sender"><a href=<?php echo $_SESSION["invite_url"]; ?>><i class="fa-regular fa-paper-plane"></i></a></span>
                                        <span style="font-size:32px;margin-left:3%"class="clos">&times;</span>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        
    
                        <div class="right">
                            <div class="description">
                                <h3 class="description">Description</h3>
                                <p class="decript-text"><?php echo $project_details["project_description"] ?><br><br></p>
                            </div>
    
                            <div class="requested-members">
                                <h3 class="description">Requested Members</h3>
                                    
                                    <?php  
                                        // check if the user viewing the page is the admin
                                        $is_admin = is_project_admin($_SESSION["user_id"], $project_id);

                                        if ($is_admin) {
                            
                                            // retrieve all people who have requested to join the project
                                            $requested_members = get_requested_project_members($project_id);
                            
                                            // loop through the requested members and echo them with accept and decline options
                                            if ($requested_members == -1) {
                                                echo "No members have requested to join the project!";
                                            } else {
                                                
                                                foreach ($requested_members as $requested_member) {
                                                    // retrieve requested_member details
                                                    $member_details = get_user_details($requested_member[$USER_ID_INDEX], "user_id");

                                                    echo 
                                                    '<div class="requested-item">
                                                        <div class="item-name">' . $member_details["firstname"] . ' ' . $member_details["lastname"] . '</div>' .
                                                        '<div class="item-controls">
                                                            <div class="accept-item">
                                                                <a href="accept_member.php?user_id=' . $requested_member[$USER_ID_INDEX] . '&project_id=' . 
                                                                $requested_member[$PROJECT_ID_INDEX] . '"><i class="fa-regular fa-square-check"></i></a>
                                                            </div>
                                                            <div class="decline-item"> 
                                                                <a href="delete_request.php?user_id=' . $requested_member[$USER_ID_INDEX] . '&project_id=' . 
                                                                $requested_member[$PROJECT_ID_INDEX] . '&action=delete-request"><i class="fa-regular fa-circle-xmark"></i></a>
                                                            </div>
                                                        </div>';
                            
                                                }
                                            }
                            
                                            $_SESSION["invite_url"] = "invite_member.php?project_id=" . $project_id ;
                                        }
                                    ?>

                                </div>
    
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>

    

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script>
        var modal = document.getElementById("form");

            // Get the button that opens the modal
            var span = document.getElementsByClassName("close")[0];
            var cancel = document.getElementsByClassName("clos")[0];
            var members = document.getElementById('invitation');
            
            // Get the <span> element that closes the modal
            
            var btn2 = document.getElementById("add-member");
            
            cancel.addEventListener('click', ()=>{
                members.style.display = "none";
            })


            btn2.addEventListener('click',()=>{
                modal.style.display="block";
            })
            
            span.addEventListener('click', ()=>{
                modal.style.display = "none";
            })
            var dots = document.getElementsByClassName("visibility2");
            var tools = document.getElementsByClassName("tooltiptext")[0];
            var eyeslash = document.getElementById("eye-slash");
            var lock = document.getElementById("lock");
            var invite = document.getElementById('button-text');
            var add_member = document.getElementById('invite-member');
            
            document.getElementsByClassName("visibility2").onclick = function() {showform()};
            function showform(){

                var x = document.getElementsByClassName("tooltiptext")[0]
                 if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }
            eyeslash.onclick= function(){
                if (eyeslash.className == 'fa-solid fa-eye'){
                    eyeslash.className = 'fa-solid fa-eye-slash';
                }
                else{
                    eyeslash.className = 'fa-solid fa-eye';
                }
            }
            lock.onclick = function(){
                if (lock.className == 'fa-sharp fa-solid fa-lock'){
                    lock.className = 'fa-solid fa-lock-open';
                }
                else{
                    lock.className = 'fa-sharp fa-solid fa-lock';
                }
            }
            function showinput(){
                var members = document.getElementById('invitation');
                modal.style.display = "block";
            }  
            add_member.onclick=function(){
                members.style.display='block';
            }        

            span.onclick=function(){
                modal.style.display="none";
            }
            
   
    </script>
    <!-- <script src="assets/js/theme.js"></script> -->
</body>

</html>
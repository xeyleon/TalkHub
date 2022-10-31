<?php 

require_once("config.php");

function isUserNameExist($connect, $username){
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($connect, $sql)){
            // Bind variables
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = trim($username);
            
            // Execute query
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
            		mysqli_stmt_close($stmt);
                   	return true;
                }
                else {
                	mysqli_stmt_close($stmt);
                    return false;
                }
            } else{
            	mysqli_stmt_close($stmt);
                echo "[Error]isUserNameExist: Something went wrong.";
            }
        }
}

function isUserExist($connect, $user_id){
        $sql = "SELECT * FROM users WHERE id = ?";
        
        if($stmt = mysqli_prepare($connect, $sql)){

            mysqli_stmt_bind_param($stmt, "s", $user_id);
            
            // Execute query
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
            		mysqli_stmt_close($stmt);
                   	return true;
                }
                else {
                	mysqli_stmt_close($stmt);
                    return false;
                }
            } else{
            	mysqli_stmt_close($stmt);
                echo "[Error]isUserExist: Something went wrong.";
            }
        }
}

function registerAccount($connect, $username, $password){
    $sql = "INSERT INTO users (username, password, registration) VALUES (?, ?, NOW())";
     
    if($stmt = mysqli_prepare($connect, $sql)){
        // Bind variables
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
        // Set parameters
        $param_username = $username;
        // Create a password hash
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
        	mysqli_stmt_close($stmt);
        	return true;
        } else{
            mysqli_stmt_close($stmt);
            return false;
        }
    }
}

function loginAccount($connect, $username, $password){
    $sql = "SELECT id, username, password, avatar FROM users WHERE username = ?";
    
    if($stmt = mysqli_prepare($connect, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);
            // Check if username exists
            if(mysqli_stmt_num_rows($stmt) == 1){
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $avatar);
                if(mysqli_stmt_fetch($stmt)){
                	// Verify Password
                    if(password_verify($password, $hashed_password)){
                        // Update Last Login
                        $sql = "UPDATE `users` SET `last_login` = NOW() WHERE `id` = ?;";
                        $stmt = mysqli_prepare($connect, $sql);
                        mysqli_stmt_bind_param($stmt, "s", $id);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        
                       	session_start();
					    // Store data in session variable
					    $_SESSION["loggedin"] = true;
					    $_SESSION["user_id"] = $id;
					    $_SESSION["username"] = $username;    
					    $_SESSION["avatar"] = $avatar;
					    
                        return true;
                    }
                    else {
                        return false;
                    }
                }
            }
            else {
            	mysqli_stmt_close($stmt);
                return false;
            }
        }
        else {
        	echo "[Error]loginAccount: Something went wrong.";
        	mysqli_stmt_close($stmt);
        	return false;
        }    
    }
}

function getTopicTitle($connect, $topic_id){
	$sql = "SELECT title AS title FROM topics WHERE topic_id = ?";
     
    if($stmt = mysqli_prepare($connect, $sql)){
        // Bind variables
        mysqli_stmt_bind_param($stmt, "s", $topic_id);

		if (mysqli_stmt_execute($stmt)){
			$results = $stmt->get_result();
			$row = mysqli_fetch_array($results);
			$topic_title = $row['title'];
			mysqli_stmt_close($stmt);
			$results->free();
			return $topic_title;
		}
	}
	else {
		echo "[Error]getTopicTitle: Something went wrong.";
		mysqli_stmt_close($stmt);
		return null;
	}
}

function isTopicExist($connect, $topic_id){
        $sql = "SELECT topic_id FROM topics WHERE topic_id = ?";
        
        if($stmt = mysqli_prepare($connect, $sql)){

            mysqli_stmt_bind_param($stmt, "s", $topic_id);
            
            // Execute query
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
            		mysqli_stmt_close($stmt);
                   	return true;
                }
                else {
                	mysqli_stmt_close($stmt);
                    return false;
                }
            } else{
            	mysqli_stmt_close($stmt);
                echo "[Error]isTopicExist: Something went wrong.";
            }
        }
}

function getTopics($connect, &$topics){
	$query = "SELECT * FROM topics ";
	$query .= "LEFT JOIN users ON topics.created_by = users.id ";
	$query .= "ORDER BY created_at ASC";
	$results = mysqli_query($connect,$query);
	
	$topics = array();
	while($row = mysqli_fetch_array($results)){
		array_push($topics, $row);
	}
	$results->free();
}

function addViewCount($connect, $topic_id){
	$sql = "UPDATE topics SET views=views+1 WHERE topic_id = ?;";
	
	if($stmt = mysqli_prepare($connect, $sql)){
		mysqli_stmt_bind_param($stmt, "s", $topic_id);
		
        if(mysqli_stmt_execute($stmt)){
    		mysqli_stmt_close($stmt);
           	return true;
        } else{
        	mysqli_stmt_close($stmt);
            echo "[Error]addTopicView: Something went wrong.";
            return false;
        }
	}
	else {
		mysqli_stmt_close($stmt);
        echo "[Error]addTopicView: Something went wrong.";
        return false;
	}
}

function addPostCount($connect, $user_id, $topic_id){
	$sql = "UPDATE topics SET posts=posts+1 WHERE topic_id = ?;";
	
	if($stmt = mysqli_prepare($connect, $sql)){
		mysqli_stmt_bind_param($stmt, "s", $topic_id);
		
        if(mysqli_stmt_execute($stmt)){
    		mysqli_stmt_close($stmt);
        } else{
        	mysqli_stmt_close($stmt);
            echo "[Error]addPostCount: Something went wrong.";
            return false;
        }
	}

	$sql = "UPDATE users SET post_count=post_count+1 WHERE id = ?;";
	
	if($stmt = mysqli_prepare($connect, $sql)){
		mysqli_stmt_bind_param($stmt, "s", $user_id);
		
        if(mysqli_stmt_execute($stmt)){
    		mysqli_stmt_close($stmt);
           	return true;
        } else{
        	mysqli_stmt_close($stmt);
            echo "[Error]addPostCount: Something went wrong.";
            return false;
        }
	}	
	
}

function getPosts($connect, $topic_id, &$posts){
	$sql = "SELECT users.username AS username, topics.title AS title, posts.content AS post, posts.timestamp AS timestamp, users.avatar AS avatar, users.id AS user_id FROM posts ";
	$sql .= "LEFT JOIN users ON posts.user_id = users.id ";
	$sql .= "LEFT JOIN topics ON posts.topic_id = topics.topic_id ";
	$sql .= "WHERE posts.topic_id = ? ";
	$sql .= "ORDER BY posts.timestamp ASC";
	
	if($stmt = mysqli_prepare($connect, $sql)){
		mysqli_stmt_bind_param($stmt, "s", $topic_id);
		
		if (mysqli_stmt_execute($stmt)){
			$results = $stmt->get_result();
			
			while($row = mysqli_fetch_array($results)){
				array_push($posts, $row);
			}
			
			mysqli_stmt_close($stmt);
			$results->free();
		}
	}
	else {
		echo "[Error]getTopicTitle: Something went wrong.";
		mysqli_stmt_close($stmt);
		return null;
	}	
}

function createTopic($connect, $title, $user_id){
	$sql = "INSERT INTO topics (title, created_by, created_at) VALUES (?, ?, NOW())";
	
    if($stmt = mysqli_prepare($connect, $sql)){
        // Bind variables
        mysqli_stmt_bind_param($stmt, "ss", $title, $user_id);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
        	$topic_id = mysqli_insert_id($connect);
        	mysqli_stmt_close($stmt);
        	return $topic_id;
        } else{
            mysqli_stmt_close($stmt);
            return null;
        }
    }
}

function createPost($connect, $topic_id, $user_id, $content){
    // Prepare an insert statement
    $sql = "INSERT INTO posts (topic_id, user_id, content, timestamp) VALUES (?, ?, ?, NOW())";
     
    if($stmt = mysqli_prepare($connect, $sql)){
        // Bind variables
        mysqli_stmt_bind_param($stmt, "sss", $topic_id, $user_id, $content);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
        	addPostCount($connect, $user_id, $topic_id);
        	mysqli_stmt_close($stmt);
        	return true;
        } else{
            mysqli_stmt_close($stmt);
            return false;
        }
    }
}

function getAvatar($connect, $user_id){
	$sql = "SELECT avatar AS avatar FROM users WHERE id = ?";
     
    if($stmt = mysqli_prepare($connect, $sql)){
        // Bind variables
        mysqli_stmt_bind_param($stmt, "s", $user_id);

		if (mysqli_stmt_execute($stmt)){
			$results = $stmt->get_result();
			$row = mysqli_fetch_array($results);
			$avatar = $row['avatar'];
			mysqli_stmt_close($stmt);
			$results->free();
			return $avatar;
		}
	}
	else {
		echo "[Error]getAvatar: Something went wrong.";
		mysqli_stmt_close($stmt);
		return null;
	}
}

function setAvatar($connect, $user_id, $avatar){
	$sql = "UPDATE users SET avatar = ? WHERE id = ?";
	
	if($stmt = mysqli_prepare($connect, $sql)){
		mysqli_stmt_bind_param($stmt, "ss", $avatar, $user_id);
		
        if(mysqli_stmt_execute($stmt)){
    		mysqli_stmt_close($stmt);
           	return true;
        } else{
        	mysqli_stmt_close($stmt);
            echo "[Error]setAvatar: Something went wrong.";
            return false;
        }
	}
	else {
		mysqli_stmt_close($stmt);
        echo "[Error]setAvatar: Something went wrong.";
        return false;
	}
}

function getUserProfile($connect, $user_id, &$profile){
	$sql = "SELECT username, avatar, registration, last_login, post_count FROM users WHERE id = ?";
	
	if($stmt = mysqli_prepare($connect, $sql)){
		mysqli_stmt_bind_param($stmt, "s", $user_id);
		
		if (mysqli_stmt_execute($stmt)){
			$results = $stmt->get_result();
			
			$profile = mysqli_fetch_array($results);
			
			mysqli_stmt_close($stmt);
			$results->free();
		}
	}
	else {
		echo "[Error]getUserProfile: Something went wrong.";
		mysqli_stmt_close($stmt);
		return null;
	}	
}

function setAccountPassword($connect, $user_id, $password){
	$sql = "UPDATE users SET password = ? WHERE id = ?";
	
	if($stmt = mysqli_prepare($connect, $sql)){
		$password = password_hash($password, PASSWORD_DEFAULT);
		mysqli_stmt_bind_param($stmt, "ss", $password, $user_id);
		
        if(mysqli_stmt_execute($stmt)){
    		mysqli_stmt_close($stmt);
           	return true;
        } else{
        	mysqli_stmt_close($stmt);
            echo "[Error]setPassword: Something went wrong.";
            return false;
        }
	}
	else {
		mysqli_stmt_close($stmt);
        echo "[Error]setPassword: Something went wrong.";
        return false;
	}
}

?>
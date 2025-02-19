
<?php include 'navbar.php'; ?>

<?php
require_once 'classes/front.php';
$front = new Front();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {



$email_to='aankitbelal@gmail.com';
$subject='Feedback';
$message=$_POST['message'];
$headers='From: '.$_POST['email'];
try{
    $submitFeedback = $front->submitFeedback($_POST);
    $sendMail = $front->sendMail($email_to, $subject, $message, $headers);
   
   if($submitFeedback && $sendMail){
     $_SESSION['suck'] = "Feedback submitted successfully!";

   }
    else{
     $_SESSION['err'] = "Failed to submit feedback!";
    }

}
catch (Exception $e) {
    $_SESSION['err'] = "Failed to submit feedback!";
   
}
}






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - AnyNotes</title>
    <link rel="stylesheet" href="./css/nav.css">
    <style>
         
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            margin-top: 80px; /* Added margin-top to create space below the navbar */
            overflow: hidden;
        }

        /* Contact Us Heading */
        h1 {
            text-align: center;
            color: #e91e63; /* Pink color for Contact Us heading */
            font-size: 2rem;
            margin-bottom: 20px;
        }

        /* Contact Info Section */
        .contact-info {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
        }
        .contact-info div {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex:  1 calc(10% - 20px); /* Two columns with gap */
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .contact-info div:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .contact-info i {
            font-size: 2rem;
            color: #ff5722; /* Orange color for icons */
            margin-bottom: 10px;
        }
        .contact-info h2 {
            color: #673ab7; /* Purple color for email and phone labels */
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .contact-info a {
            color: #1a73e8;
            text-decoration: none;
        }
        .contact-info a:hover {
            color: #34a853;
            text-decoration: underline;
        }
          /* Message styling */
          .message {
            color: white; /* White text for better contrast */
            text-align: center;
            margin-bottom: 15px;
            padding: 10px; /* Add padding for better appearance */
            border-radius: 5px; /* Rounded corners */
            display: none; /* Initially hidden */
            margin-top: 10px;
        }

        /* Error message background color */
        .message.error {
            background-color: #ff4444; /* Red background for errors */
        }

        /* Success message background color */
        .message.success {
            background-color: #4CAF50; /* Green background for success */
        }
        /* General Styles */

        /* Feedback Form Section */
        .feedback-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 40px;
            max-width: 600px; /* Limit form width */
            margin: 0 auto; /* Center the form */
        }
        .feedback-form h2 {
            color: #009688; /* Teal color for Feedback heading */
            font-size: 1.8rem;
            margin-bottom: 15px;
            text-align: center; /* Center the header */
        }
        .feedback-form input, .feedback-form textarea {
            width: 80%; /* Decrease width of input fields */
            padding: 12px;
            margin: 0 auto 20px; /* Center input fields */
            display: block; /* Ensure inputs are block-level for centering */
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .feedback-form input:focus, .feedback-form textarea:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 8px rgba(26, 115, 232, 0.3);
            outline: none;
        }
        .feedback-form textarea {
            resize: vertical;
            height: 150px;
        }

        /* Centered Gradient Button */
        .feedback-form .button-container {
            text-align: center; /* Center the button */
        }
        .feedback-form button {
            background: linear-gradient(45deg, #1a73e8, #34a853); /* Gradient background */
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feedback-form button:hover {
            background: linear-gradient(45deg, #34a853, #1a73e8); /* Reverse gradient on hover */
            transform: translateY(-2px); /* Lift effect */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
        }

        /* Social Media Section */
        .social-media {
            text-align: center;
            margin-top: 40px;
        }
        .social-media a {
            margin: 0 10px;
            color: #1a73e8;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }
        .social-media a:hover {
            color: #34a853;
        }

        /* Responsive Design for Mobile */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem; /* Smaller font size for mobile */
            }

            .contact-info {
                gap: 15px; /* Reduce gap for mobile */
            }
            .contact-info div {
                flex: 1 1 calc(50% - 10px); /* Keep side-by-side with reduced gap */
                padding: 15px; /* Reduce padding for mobile */
            }
            .contact-info i {
                font-size: 1.5rem; /* Smaller icons for mobile */
            }
            .contact-info h2 {
                font-size: 1.2rem; /* Smaller font size for mobile */
            }

            .feedback-form {
                padding: 20px; /* Reduce padding for mobile */
            }
            .feedback-form input, .feedback-form textarea {
                width: 100%; /* Full width for mobile */
            }

            .feedback-form h2 {
                font-size: 1.5rem; /* Smaller font size for mobile */
            }

            .social-media a {
                font-size: 1.2rem; /* Smaller icons for mobile */
            }
        }

        @media (max-width: 480px) {
            .contact-info div {
                flex: 1 1 100%; /* Stack vertically on very small screens */
            }
        }
    </style>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
 

    <div class="container">

    <div class="message" id="message">
            <?php if (isset($_SESSION['err'])): ?>
                <?php echo $_SESSION['err']; ?>
                <?php unset($_SESSION['err']); ?>
            <?php elseif (isset($_SESSION['sucks'])): ?>
                <?php echo $_SESSION['sucks']; ?>
                <?php unset($_SESSION['sucks']); ?>
            <?php endif; ?>
        </div>
        <h1>Contact Us</h1>
        

        <!-- Contact Information -->
        <div class="contact-info">
            <div>
                <i class="fas fa-envelope"></i>
                <h2>Email</h2>
                <p><a href="mailto:ankitswarswati@gmail.com">ankitswarswati@gmail.com</a></p>
            </div>
            <div>
                <i class="fas fa-phone"></i>
                <h2>Phone</h2>
                <p><a href="tel:+977-9815613652">+977-9815613652</a></p>
            </div>
        </div>

        <!-- Stylish Feedback Form -->
        <div class="feedback-form">
            <h2>Feedback</h2> <!-- Teal color for Feedback heading -->
            <form action="#" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Feedback" required></textarea>
                <div class="button-container">
                    <button type="submit" name ="submit">Submit Feedback</button>
                </div>

              

            </form>
        </div>



   
    </div>
</body>
</html>


<?php include 'footer.php'; ?>




<script>
        // JavaScript to handle error and success messages
        document.addEventListener('DOMContentLoaded', function() {
            var messageDiv = document.getElementById('message');

            // Check if the message div contains any content
            if (messageDiv && messageDiv.textContent.trim() !== '') {
                // Determine if it's an error or success message
                if (messageDiv.textContent.includes('Invalid')) {
                    messageDiv.classList.add('error'); // Add error class for red background
                } else {
                    messageDiv.classList.add('success'); // Add success class for green background
                }

                // Show the message div
                messageDiv.style.display = 'block';

                // Hide the message after 5 seconds
                setTimeout(function() {
                    messageDiv.style.display = 'none';
                }, 3000); // 5 seconds

                // Redirect to reset.php if it's a success message
                if (messageDiv.classList.contains('success')) {
                    setTimeout(function() {
                    
                        window.location.href = 'Contact.php'; // Redirect to reset.php
                    }, 1000); // 1 second
                }
            }
        });

        </script>
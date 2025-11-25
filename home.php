<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>home page</title>

  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
 
  <link rel="stylesheet" href="styles/navbar.css?v=3" />
  <link rel="stylesheet" href="styles/style2.css">

</head>

<body>
    <?php include("navbar.php") ?>

  <main class="hero-section">
    <div class="hero-text">
      <h1>
        Expert care for your mental health and wellness
        <span class="yellow-dot">•</span>
      </h1>
      <p>
        Medical expert provides professional support to keep you and your
        loved ones safe
      </p>
      <div class="buttons">
        <button class="btn-yellow" onclick="location.href='messaging-page.php'">Send A Message</button>
        <button class="btn-gray" onclick="location.href='get-reply.php'">Check for Reply</button>
      </div>
    </div>

    <div class="hero-image">
      <img src="icons/brain1.png" alt="Mental health illustration">
    </div>

  </main>

  <div class="steps">
    <div class="step">
      <div class="circle">1</div>
      <p>Send a message</p>
    </div>

    <div class="step">
      <div class="circle">2</div>
      <p>Get a code</p>
    </div>

    <div class="step">
      <div class="circle">3</div>
      <p>Retrieve the reply</p>
    </div>

  </div>

  <div class="label">
    <h2>No sign up required
    </h2>
    <p>100% Anonymous</p>

  </div>


  <div class="container">

    <div class="card" onclick="location.href='messaging-page.php'">
      <img src="icons/meeting-alt.png" alt="send message">
      <p>send a message</p>
    </div>

    <div class="card" onclick="location.href='get-reply.php'">
      <img src="icons/envelope.png" alt="retrieve reply">
      <p>retrieve a reply</p>
    </div>
    <div class="card" onclick="location.href='library.php'">
      <img src="icons/book.png" alt="browse the library">
      <p>browse the library</p>
    </div>

    <div class="card" onclick="location.href='public-page.php'">
      <img src="icons/bubble-discussion.png" alt="retrieve reply">
      <p>check public Q&A</p>
    </div>
    <div class="card" onclick="location.href='assessments.php'">
      <img src="icons/journal-alt.png" alt="send message">
      <p>take an assessment</p>
    </div>

    <div class="card" onclick="location.href='get-reply.php'">
      <img src="icons/button-chat.png" alt="retrieve reply">
      <p>chat with an AI bot</p>
    </div>
  </div>

  <div class="disclaimer">

    <h1>Disclaimer</h1>
    <p>
      <span class="bold-text"> 1. Not a Substitute for Emergency Help: </span> If you are in crisis or believe you may
      be in danger, do not
      use this
      website. This service is not intended for emergencies. If you are experiencing a mental health emergency, please
      contact your local emergency number or reach out to one of the hotlines
    </p>
    <p> <span class="bold-text">2. No Therapist–Patient Relationship: </span>Use of this website does not create a
      therapist–patient or professional
      relationship between you and the therapist. Any responses provided through this platform are for general
      informational and supportive purposes only, and should not be considered a substitute for professional diagnosis,
      treatment, or therapy.</p>
    <p> <span class="bold-text">3. Limitations of Liability:</span> This website and its operators assume no
      responsibility or liability for any loss,
      harm, or damage arising from your reliance on information provided through this service. You are solely
      responsible for how you interpret and act upon the content or advice shared here.</p>
    <p> <span class="bold-text">4. Content Accuracy:</span>While every effort is made to provide accurate and helpful
      responses, no guarantee is made
      regarding the accuracy, completeness, or reliability of the content on this website. The information provided
      should not replace consultation with a qualified mental health professional.
    </p>


  </div>


</body>

</html>

<script src="scripts/script.js" defer></script>
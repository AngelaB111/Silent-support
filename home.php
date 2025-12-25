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

  <link rel="stylesheet" href="styles/navbar.css?v=9" />
  <link rel="stylesheet" href="styles/style2.css?v=6">

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


  <div class="line"> </div>
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
  <div class="line"> </div>
  <section class="disclaimer">
    <h1>Disclaimer</h1>
    <div class="disclaimer-grid">

      <div class="disclaimer-item">
        <i class="fa fa-ambulance"></i>
        <p><span class="bold-text">Not an Emergency Service</span><br>If you are in crisis, please contact your local
          emergency number immediately.</p>
      </div>

      <div class="disclaimer-item">
        <i class="fa fa-user-md"></i>
        <p><span class="bold-text">No Patient Relationship</span><br>Use of this site does not create a formal
          therapist-patient relationship.</p>
      </div>

      <div class="disclaimer-item">
        <i class="fa fa-shield"></i>
        <p><span class="bold-text">Limit of Liability</span><br>We assume no responsibility for actions taken based on
          the information provided here.</p>
      </div>

      <div class="disclaimer-item">
        <i class="fa fa-check-circle"></i>
        <p><span class="bold-text">Content Accuracy</span><br>Responses are for support purposes and are not a
          substitute for professional diagnosis.</p>
      </div>

    </div>
  </section>

  <?php include("footer.php") ?>

</body>

</html>
<script>
  (function () { if (!window.chatbase || window.chatbase("getState") !== "initialized") { window.chatbase = (...arguments) => { if (!window.chatbase.q) { window.chatbase.q = [] } window.chatbase.q.push(arguments) }; window.chatbase = new Proxy(window.chatbase, { get(target, prop) { if (prop === "q") { return target.q } return (...args) => target(prop, ...args) } }) } const onLoad = function () { const script = document.createElement("script"); script.src = "https://www.chatbase.co/embed.min.js"; script.id = "oQ0ArFnpi0Wid0R4FeVdd"; script.domain = "www.chatbase.co"; document.body.appendChild(script) }; if (document.readyState === "complete") { onLoad() } else { window.addEventListener("load", onLoad) } })();
</script>
<script src="scripts/script.js" defer></script>
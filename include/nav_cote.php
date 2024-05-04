<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="home/Home.css">
  <title>e-Service</title>
  <style>
    :root {
  --body-bg: rgb(243, 243, 243);
  --nav-bg: #555ce3;
}

* {
  box-sizing: border-box;
  font-family: poppins;
  margin: 0;
  padding: 0;
}

body {
  font-size: 1rem;
  background: var(--body-bg);
  height: 100vh;

}
#sidebar {
  background: var(--nav-bg);
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  padding: 2rem 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3rem;
}

.nav-list {
  list-style: none;
  display: grid;
  gap: 1rem;
  position: relative;
  font-size: small;
}

.nav-list a {
  color: white;
  display: block;
  text-decoration: none;
  opacity: 80%;
  width: 100%;
  transition: all 0.1s;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}
.nav-list a:hover {
  opacity: 100%;
}

.nav-list li {
  padding-block: 1rem;
  padding-inline: 2rem;
  margin-inline: 1rem 0;
  box-shadow: 2px 0 0 var(--body-bg);
}
.arrow-left {
  width: 0;
  height: 0;
  border-top: 7px solid transparent; /* Top side of the arrow */
  border-bottom: 7px solid transparent; /* Bottom side of the arrow */
  border-left: 5px solid rgb(255, 255, 255); /* Right side of the arrow */
  opacity: 80%;
}
.nav-list a:hover ~ .arrow-left {
  opacity: 100%;
  transform: translateX(5%);
}

.nav-list li.active {
  background: var(--body-bg);
  position: relative;
  border-radius: 100vw 0 0 100vw;
}
.nav-list li.active a {
  color: rgb(0, 0, 0);
  opacity: 100%;
}
.nav-list li.active .arrow-left {
  border-left: 5px solid rgb(0, 0, 0);
}
.nav-list li.active path {
  fill: black;
}

.nav-list li.active::before,
.nav-list li.active::after {
  --border-radius: 1rem;
  content: "";
  position: absolute;
  width: var(--border-radius);
  height: var(--border-radius);
  right: 0rem;
}

.nav-list li.active::before {
  border-radius: 0 0 var(--border-radius);
  top: calc(var(--border-radius) * -1);
  box-shadow: 5px 5px 0 5px var(--body-bg);
}

.nav-list li.active::after {
  border-radius: 0 var(--border-radius) 0 0;
  bottom: calc(var(--border-radius) * -1);
  box-shadow: 5px -5px 0 5px var(--body-bg);
}
.imgcontainer {
  position: relative;
}

.logo {
  height: 3rem;
  width: fit-content;
}
.imgcontainer::after,
.nav-list::after {
  content: "";
  width: 10rem;
  height: 0.5px;
  display: block;
  position: absolute;
  background-color: rgb(197, 194, 255);
  left: 50%;
  transform: translateX(-50%);
  bottom: -35%;
  opacity: 50%;
}
li svg path {
  fill: white;
}
li svg {
  height: 1.5rem;
}
.group {
  display: flex;
  align-items: center;
  gap: 1rem;
}
.nav-list::after {
  bottom: -1rem;
}

/*navbar*/

header {
  position: fixed;
  top: 0;
  padding: 2rem;
  padding-left: 17rem;
  width: 100%;
  height: 2.5rem;
  background-color: rgb(255, 255, 255);
  box-shadow: 10px 3px 10px rgb(211, 211, 211);
  display: flex;
  align-items: center;
}

.profile {
  border: 2px solid rgb(0, 0, 0);
  border-radius: 50%;
  height: 2.5rem;
  width: 2.5rem;
  display: grid;
  place-items: center;
}
.profile svg path {
  fill: rgb(0, 0, 0);
}
.profile svg {
  height: 1.5rem;
  width: 1.5rem;
}
.bodyDiv{
padding-top: 100px;
padding-left: 300px;

}
  </style>
</head>

<body>
  <header>
    <div class="group">
      <a class="profile" href="profil.php"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
          <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z" />
        </svg></a>
    </div>


    <!-- <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success searchButton" type="submit">Search</button>
      </form> -->



  </header>





  <nav id="sidebar">
    <div class="imgcontainer">
      <img class="logo" src="../public/images/logo-ensah.png" alt="ensah">
    </div>
    <ul class="nav-list">
      <li class="active">
        <a href="index.php" aria-current="page">
          <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
              <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
            </svg>Acceuille</div>
          <div class="arrow-left"></div>
        </a>
      </li>
      <li>
        <a href="empTmps/empTmps.php">
          <div class="group">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
              <path d="M320-400q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm160 0q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm160 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
            </svg>Emploi du temps
          </div>
          <div class="arrow-left"></div>
        </a>
      </li>
      <li>
        <a href="affichage/affichage.php">
          <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
              <path d="M360-120q-33 0-56.5-23.5T280-200v-400q0-33 23.5-56.5T360-680h400q33 0 56.5 23.5T840-600v400q0 33-23.5 56.5T760-120H360Zm0-400h400v-80H360v80Zm160 160h80v-80h-80v80Zm0 160h80v-80h-80v80ZM360-360h80v-80h-80v80Zm320 0h80v-80h-80v80ZM360-200h80v-80h-80v80Zm320 0h80v-80h-80v80Zm-480-80q-33 0-56.5-23.5T120-360v-400q0-33 23.5-56.5T200-840h400q33 0 56.5 23.5T680-760v40h-80v-40H200v400h40v80h-40Z" />
            </svg>Affichage</div>
          <div class="arrow-left"></div>
        </a>
      </li>
      <li><a href="message.php">
          <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
              <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
            </svg>Messagerie</div>
          <div class="arrow-left"></div>
        </a></li>
      <li><a href="personnel.php">
          <div class="group"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
              <path d="M440-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T520-640q0-33-23.5-56.5T440-720q-33 0-56.5 23.5T360-640q0 33 23.5 56.5T440-560ZM884-20 756-148q-21 12-45 20t-51 8q-75 0-127.5-52.5T480-300q0-75 52.5-127.5T660-480q75 0 127.5 52.5T840-300q0 27-8 51t-20 45L940-76l-56 56ZM660-200q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-540 40v-111q0-34 17-63t47-44q51-26 115-44t142-18q-12 18-20.5 38.5T407-359q-60 5-107 20.5T221-306q-10 5-15.5 14.5T200-271v31h207q5 22 13.5 42t20.5 38H120Zm320-480Zm-33 400Z" />
            </svg>Personnel</div>
          <div class="arrow-left"></div>
        </a></li>
    </ul>
  </nav>
  <div class="bodyDiv">

  </div>

</body>

</html>
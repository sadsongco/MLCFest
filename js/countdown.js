/** ****Functions**** */

const checkCountdownTarget = function (today) {
  const navContainer = document.getElementsByTagName('nav')[0];
  navContainer.style.display = 'block';
  if (dev) {
    switch (devSel.value) {
      case 'launch':
        navContainer.style.display = 'none';
        return content.launch;
      case 'presale':
        return content.presale;
      case 'onsale':
        return content.onsale;
      case 'false':
        return content.general;
    }
  }
  if (today >= content.onsale.date) return content.general;
  if (today >= content.presale.date) return content.onsale;
  if (today >= content.launch.date) return content.presale;
  // if it's the launch countdown, we don't need navigation
  navContainer.style.display = 'none';
  return content.launch;
};

const timeToGo = function (d, today) {
  let diff = d - today;

  // Allow for previous times
  diff = Math.abs(diff);

  // Get time components
  const days = (diff / (3.6e6 * 24)) | 0;
  const hours = ((diff / 3.6e6) | 0) - days * 24;
  const mins = ((diff % 3.6e6) / 6e4) | 0;
  const secs = Math.round((diff % 6e4) / 1e3);
  // Return formatted string
  return [days, hours, mins, secs];
};

const doCountdown = () => {
  const today = new Date();
  const countdownContainer = document.getElementById('countdown');
  const countdownTitleContainer = document.getElementById('countdown-title');
  const currentStatus = checkCountdownTarget(today);
  if (JSON.stringify(currentStatus) !== JSON.stringify(status)) {
    status = currentStatus;
    createCurrentPage();
  }
  if (!status.showCountdown) {
    countdownTitleContainer.innerHTML = '';
    return (countdownContainer.innerHTML = '');
  }

  countdownTitleContainer.innerHTML = status.showCountdown;
  countdownContainer.innerHTML = createCountdownString(timeToGo(status.date, today));
  return;
};

const createCountdownString = (countdown) => {
  const countdownStringArr = [];
  if (countdown[0] > 0) countdownStringArr.push(`${countdown[0]} day${countdown[0] === 1 ? '' : 's'}`);
  if (countdown[1] > 0) countdownStringArr.push(`${countdown[1]} hour${countdown[1] === 1 ? '' : 's'}`);
  if (countdown[2] > 0) countdownStringArr.push(`${countdown[2]} minute${countdown[2] === 1 ? '' : 's'}`);
  if (countdown[3] > 0) countdownStringArr.push(`${countdown[3]} second${countdown[3] === 1 ? '' : 's'}`);
  return countdownStringArr.join(', ');
};

const showLineup = (contentObj) => {
  const lineupContainer = document.getElementById('lineup-container');
  const lineupHeading = lineupContainer.getElementsByTagName('h1')[0];
  if (contentObj.lineup === false) return (lineupContainer.style.display = 'none');
  lineupContainer.style.display = 'block';
  lineupContainer.innerHTML = '';
  lineupContainer.appendChild(lineupHeading);
  const imageEl = document.createElement('img');
  const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
  imageEl.src = vw < 600 ? './assets/graphics/lineup-mobile.jpg' : './assets/graphics/lineup-desktop.jpg';
  imageEl.alt = 'Misery Loves Company festival 2023 lineup, featuring Twin Atlantic, Yonaka, Gürl, Immerse, InMe and many more';
  imageEl.style.width = Math.round(vw * 0.9);
  return lineupContainer.appendChild(imageEl);
};

const showAbout = (contentObj) => {
  const aboutContainer = document.getElementById('about-container');
  const aboutHeading = aboutContainer.getElementsByTagName('h1')[0];
  if (contentObj.about === false) return (aboutContainer.style.display = 'none');
  aboutContainer.style.display = 'block';
  aboutContainer.innerHTML = '';
  aboutContainer.appendChild(aboutHeading);
  const aboutP = document.createElement('p');
  aboutP.innerHTML = contentObj.about;
  aboutContainer.appendChild(aboutP);
};

const showTicketButton = (contentObj) => {
  const ticketLink = document.getElementById('ticket-link');
  ticketLink.href = contentObj.ticketUrl;
  ticketLink.innerHTML = contentObj.ticketText;
  document.getElementById('ticket-title').style.visibility = contentObj.ticketText === 'Tickets' ? 'hidden' : 'visibile';
  return;
};

const createCurrentPage = () => {
  // showCountdown();
  showLineup(status);
  showAbout(status);
  showTicketButton(status);
};

/** EXECUTE */

// glabal countdown interval so it can be reset
let countdownInterval;

// set content variables
const content = {
  launch: {
    date: new Date(2023, 02, 27, 10),
    showCountdown: 'Lineup Announced In',
    lineup: false,
    about: false,
    ticketText: `Sign up for the pre-sale`,
    ticketUrl: 'http://eepurl.com/imQkyA',
  },
  presale: {
    date: new Date(2023, 02, 29, 10),
    showCountdown: 'Presale starts in',
    lineup: true,
    about: `We're all sad, so let's be sad together.<br>
      Bristol's brand new alternative festival.<br>
      5 stages, 4 venues, 40+ bands.<br>
      Presale live this Wednesday 29th March, sign up for access.`,
    ticketText: 'Sign up for the pre-sale',
    ticketUrl: 'http://eepurl.com/imQkyA',
  },
  onsale: {
    date: new Date(2023, 02, 31, 10),
    showCountdown: false,
    lineup: true,
    about: `We're all sad, so let's be sad together.<br>
      Bristol's brand new alternative festival.<br>
      5 stages, 4 venues, 40+ bands.<br>
      Presale live now, sign up for access.`,
    ticketText: 'Presale',
    ticketUrl: 'http://eepurl.com/imQkyA',
  },
  general: {
    date: new Date(2023, 08, 30, 18),
    showCountdown: false,
    lineup: true,
    about: `We're all sad, so let's be sad together.<br>
      Bristol's brand new alternative festival.<br>
      5 stages, 4 venues, 40+ bands.<br>
      Tickets on sale NOW.`,
    ticketText: 'Tickets',
    ticketUrl: 'https://tourlink.to/MiseryLovesCompany',
  },
};

// temp dev variable and options *** DELETE FOR PRODUCTION ***
const dev = false;
let devSel;
if (dev) {
  const devContainer = document.getElementById('dev');
  devSel = document.createElement('select');
  const launchDev = document.createElement('option');
  launchDev.value = 'launch';
  launchDev.innerHTML = 'Countdown to lineup reveal';
  const presaleDev = document.createElement('option');
  presaleDev.value = 'presale';
  presaleDev.innerHTML = 'Countdown to presale';
  const onsaleDev = document.createElement('option');
  onsaleDev.value = 'onsale';
  onsaleDev.innerHTML = 'Presale live';
  const doneDev = document.createElement('option');
  doneDev.value = 'false';
  doneDev.innerHTML = 'General Sale live';
  devSel.appendChild(launchDev);
  devSel.appendChild(presaleDev);
  devSel.appendChild(onsaleDev);
  devSel.appendChild(doneDev);
  devContainer.appendChild(devSel);
  devSel.onchange = () => {
    createCurrentPage();
  };
}
// end dev

// the state of the festival (lineup countdown, presale, general sale)
let status = false;

// navigation listeners
const navList = document.getElementById('nav-bar').getElementsByTagName('li');
for (const liItem of navList) {
  liItem.onclick = () => {
    document.getElementById(liItem.getElementsByTagName('a')[0].dataset.target).scrollIntoView();
  };
}
// Check where we are in the process
const currentStatus = checkCountdownTarget();
if (JSON.stringify(currentStatus) !== JSON.stringify(status)) status = currentStatus;
countdownInterval = setInterval(doCountdown, 1000);
createCurrentPage();

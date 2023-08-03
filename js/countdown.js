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
  if (today >= content.secondWave.date) return content.secondWave;
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
  if (JSON.stringify(currentStatus) !== JSON.stringify(showStatus)) {
    showStatus = currentStatus;
    createCurrentPage();
  }
  if (!showStatus.showCountdown) {
    countdownTitleContainer.innerHTML = '';
    return (countdownContainer.innerHTML = '');
  }

  countdownTitleContainer.innerHTML = showStatus.showCountdown;
  countdownContainer.innerHTML = createCountdownString(timeToGo(showStatus.date, today));
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
  const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
  const imageEl = document.createElement('img');
  imageEl.src = vw < 600 ? `./assets/graphics/${contentObj.lineupGraphic}-mobile.jpg` : `./assets/graphics/${contentObj.lineupGraphic}-desktop.jpg`;
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
  showLineup(showStatus);
  showAbout(showStatus);
  showTicketButton(showStatus);
};

/** EXECUTE */

// glabal countdown interval so it can be reset
let countdownInterval;

// set content variables
const content = {
  launch: {
    date: new Date(2023, 2, 27, 10),
    showCountdown: 'Lineup Announced In',
    lineup: false,
    about: false,
    ticketText: `Sign up for the pre-sale`,
    ticketUrl: 'http://eepurl.com/imQkyA',
  },
  presale: {
    date: new Date(2023, 2, 29, 10),
    showCountdown: 'Presale starts in',
    lineup: true,
    lineupGraphic: 'lineup',
    about: `We're all sad, so let's be sad together.<br>
      Bristol's brand new alternative festival.<br>
      5 stages, 4 venues, 40+ bands.<br>
      Presale live this Wednesday 29th March, sign up for access.`,
    ticketText: 'Sign up for the pre-sale',
    ticketUrl: 'http://eepurl.com/imQkyA',
  },
  onsale: {
    date: new Date(2023, 2, 31, 10),
    showCountdown: false,
    lineup: true,
    lineupGraphic: 'lineup',
    about: `We're all sad, so let's be sad together.<br>
      Bristol's brand new alternative festival.<br>
      5 stages, 4 venues, 40+ bands.<br>
      Presale live now, sign up for access.`,
    ticketText: 'Presale',
    ticketUrl: 'http://eepurl.com/imQkyA',
  },
  secondWave: {
    date: new Date(2023, 5, 16, 10),
    showCountdown: false,
    lineup: true,
    lineupGraphic: 'update',
    about: `We're all sad, so let's be sad together.<br>
      Bristol's brand new alternative festival.<br>
      5 stages, 4 venues, 40+ bands.<br>
      Second wave of line up just announced! Joining the lineup we have Heriot, Fearless Vampire Killers, the Hara, The Xcerts, Witch Fever and 20+ other bands!<br>
      Tickets on sale NOW. Pay in 3 via Paypal at checkout!`,
    ticketText: 'Tickets',
    ticketUrl: 'https://premier.ticketek.co.uk/shows/show.aspx?sh=MISERYLO2',
  },
  general: {
    date: new Date(2023, 8, 30, 18),
    showCountdown: false,
    lineup: true,
    lineupGraphic: 'update',
    about: `We're all sad, so let's be sad together.<br>
      Bristol's brand new alternative festival.<br>
      5 stages, 4 venues, 40+ bands.<br>
      Tickets on sale NOW.`,
    ticketText: 'Tickets',
    ticketUrl: 'https://premier.ticketek.co.uk/shows/show.aspx?sh=MISERYLO2',
  },
};

// temp dev variable and options *** DELETE FOR PRODUCTION ***
const dev = false;
let devSel;

// the state of the festival (lineup countdown, presale, general sale)
let showStatus = false;

// navigation listeners
const navList = document.getElementById('nav-bar').getElementsByTagName('li');
for (const liItem of navList) {
  liItem.onclick = () => {
    document.getElementById(liItem.getElementsByTagName('a')[0].dataset.target).scrollIntoView();
  };
}
// Check where we are in the process
const currentStatus = checkCountdownTarget();
if (JSON.stringify(currentStatus) !== JSON.stringify(showStatus)) showStatus = currentStatus;
countdownInterval = setInterval(doCountdown, 1000);
createCurrentPage();

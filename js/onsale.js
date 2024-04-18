const onsale = '19 Apr 2024 10:00:00';

const checkOnsale = (liveDateTime = new Date(onsale)) => {
  if (new Date() < liveDateTime) return;
  const link = document.getElementById('ticketLink');
  link.href = 'https://premier.ticketek.co.uk/shows/show.aspx?sh=MISLOVES24&utm_source=organic&utm_medium=website&utm_campaign=misloves24&utm_id=misloves24';
  link.innerHTML = 'Tickets On Sale For 2024 Now';
  clearInterval(timeCheck);
};

checkOnsale();

const timeCheck = setInterval(checkOnsale, 5000);

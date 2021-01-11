/*******
 * JS for novagallery.org
 * @author novafacile OÜ
 * @copyright 2021 novafacile OÜ
 *******/

/*** trigger tab ***/
function showTab(tabname) {
  // remove all active states
  let nav = document.querySelectorAll('.nav-link.active');
  nav.forEach(function(el){
    el.classList.remove('active');
  });

  let content = document.querySelectorAll('.tab-pane.active');
  content.forEach(function(el){
    el.classList.remove('active');
    el.classList.remove('show');
  });

  // add active state to element
  let active_tab = document.querySelector(tabname + '-tab');
  let active_content = document.querySelector(tabname + '-content');
  active_tab.classList.add('active');
  active_content.classList.add('active');
  active_content.classList.add('show');
}

/*** scroll to element by id ***/
function scrollTo(id){
  let el = document.querySelector(id);
  el.scrollIntoView({
    block: 'start',
    behavior: 'smooth',
    inline: 'start'
  });
}

/*** bind events ***/
document.addEventListener("DOMContentLoaded", (event) => {

  // act on anchor in url
  try {
    let anchor = window.top.location.hash;
    let firstEl = document.querySelector(anchor + '-content');
    if(firstEl){
      showTab(anchor);
      scrollTo('#content');
    }  
  } catch (e){
    // ignore
  }
  
  // more info link
  document.querySelector("#scroll-to-lead").addEventListener("click", function(){
    scrollTo('#lead');
  });

  window.addEventListener('popstate', (event) => {
    let target = window.top.location.hash;
    if(target){
      showTab(target);
      scrollTo('#content');
    } else {
      showTab('#home');
    }
    
  });

})
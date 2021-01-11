/*******
 * JS for novagallery.org
 * @author novafacile OÜ
 * @copyright 2021 novafacile OÜ
 *******/

/*** trigger tab ***/
function showTab(tabname) {
  // get new tab content
  let new_tab = document.querySelector(tabname + '-tab');
  let new_content = document.querySelector(tabname + '-content');

  // check if new content tab exists
  if(!new_content){
    // abort if not available
    return;
  }
  
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
  new_tab.classList.add('active');
  new_content.classList.add('active');
  new_content.classList.add('show');

  return true;
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

/*** anchor validator ***/
function validateAnchor(anchor){
  let res = anchor.match(/[^#\w]+/g);
  if(res){
    // return false if not match with regular anchor
    return false
  } else {
    return true;
  }
}


/*** bind events ***/
document.addEventListener("DOMContentLoaded", (event) => {

  // act on anchor in url
  try {
    let anchor = window.top.location.hash;
    if(!validateAnchor(anchor)){ // validate if regular anchor
      return false; 
    }
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
    showTab('#home');
    scrollTo('#lead');
  });

  window.addEventListener('popstate', (event) => {
    let anchor = window.top.location.hash;
    if(anchor){
      if(!validateAnchor(anchor)){ // validate if regular anchor
        return false; 
      }
      showTab(anchor);
      scrollTo('#content');
    } else {
      showTab('#home');
    }
    
  });

})
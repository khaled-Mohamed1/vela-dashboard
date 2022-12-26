// PADDING TOP IN MOBILE
window.onload = function(){
  setPaddinglocalStorage();
  window.ondeviceorientation = function(){
    setPaddinglocalStorage();
  }
  window.onresize = function(){
    setPaddinglocalStorage();
  }

  // START CHANGE LANGUAGE
  if (!localStorage.getItem("vela-dash-lang") || localStorage.getItem("vela-dash-lang") == "en") {
      lang.value = 'en';
      document.documentElement.dir = "ltr";
    } else {
      lang.value = 'ar';
      document.documentElement.dir = "rtl";
      sidebarMenu.style.left = "auto";
      sidebarMenu.style.right = "0";
      document.querySelectorAll(".nav .svg-inline--fa").forEach((e) => {
          e.style.marginRight = "0";
          e.style.marginLeft = "5px";
      });
      document.querySelector('.btn-compressed').style.transform = 'rotate(0deg)'
      dash_search.placeholder = 'بحث'
      dash_reports.textContent = "التقارير";
      dash_contact.textContent = "الإتصال";
      dash_meeting.textContent = "الإجتماع";
      dash_admins.textContent = "المشرفين";
      dash_employees.textContent = "الموظفين";
      document.querySelector('#dash_profile img').style.margin = "0 0 0 5px";
      logout.style.marginLeft = '0';
      logout.style.marginRight = 'auto';
      logout.style.transform = 'rotate(180deg)';
  }
  // END CHANGE LANGUAGE
}

// CHANGE EXIT LANG
function changeModalLang(){
  if(localStorage.getItem("vela-dash-lang") == "ar"){
    document.querySelector('#dash_exit_content') ? document.querySelectorAll('#dash_exit_content').forEach(e => e.textContent = "هل تريد تسجيل الخروج؟") : '';
    document.querySelectorAll('#delete_modal_no').forEach(e => e.textContent = "لا");
    document.querySelectorAll('#delete_modal_no').forEach(e => e.textContent = "نعم");
  }
}

// CHANGE LANGUAGE SELECT BUTTON
function changeLanguage() {
  localStorage.setItem("vela-dash-lang", event.target.value);
  window.location.reload();
}

function setPaddinglocalStorage(){
  if(window.innerWidth <= 767.9){
    localStorage.setItem('body-padding-top', 'true');
  }else{
    localStorage.setItem('body-padding-top', 'false')
  }
}

// ACTIVE LINK background-color
let links = document.querySelectorAll('.nav.flex-column .nav-link');

links.forEach(function(el){
  el.addEventListener('click', function(){
    if(this.id === 'logout'){
      return false;
    }
    links.forEach((e)=>{
      e.classList.remove('active');
    });
    el.classList.add('active');
  });
});

// DIRECTING SIDEBAR LINKS
sidebarLinks('#reports-link', 'http://127.0.0.1:8000/admin/dashboard/reports');
sidebarLinks('#users-link', 'http://127.0.0.1:8000/admin/dashboard/users');
sidebarLinks('#admins-link', 'http://127.0.0.1:8000/admin/dashboard/admins');
// sidebarLinks('#companies-link', 'http://127.0.0.1:8000/admin/dashboard/companies');

function sidebarLinks(id, dest){
  document.querySelector(id).addEventListener('click', function(){
    document.querySelector('iframe').setAttribute('src', `${dest}`);
    if(document.body.offsetWidth <= 767.9){
      document.querySelector('.navbar-toggler').click();
    }
  });
}
// toggle compressed
document.querySelector('.btn-compressed').addEventListener('click', function(){
  document.querySelector('#sidebarMenu').classList.toggle('compressed');
});

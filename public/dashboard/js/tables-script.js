// PADDING TOP IN MOBILE
window.onload = function () {
  setTimeout(() => {
    getPaddinglocalStorage();
    window.ondeviceorientation = function () {
      getPaddinglocalStorage();
    };
    window.onresize = function () {
      getPaddinglocalStorage();
    };
  }, 100);

  document.querySelector(".loading-screen").style.display = "none";
  document.body.style.overflow = 'visible';

  // START CHANGE LANGUAGE
  if (!localStorage.getItem("vela-dash-lang") || localStorage.getItem("vela-dash-lang") == "en") {
    document.documentElement.dir = "ltr";
    document.querySelector("#search_icon") ? search_icon.style.borderRadius = '.375rem 0 0 .375rem' : '';
    document.querySelector("#admins_search") ? admins_search.style.borderRadius = '0 .375rem .375rem 0' : '';
  } else {
    document.documentElement.dir = "rtl";
    document.querySelector("#heading_admins") ? heading_admins.textContent = "المشرفين" : '';
    document.querySelector("#add_admin") ? add_admin.textContent = "+ إضافة مشرف" : '';
    document.querySelector("#admins_search") ? admins_search.placeholder = "بحث" : '';
    if(document.querySelector("#show_admin_picture")){
      document.querySelectorAll('#show_admin_picture').forEach(e =>{
        e.classList.remove('me-3');
        e.classList.add('ms-3');
      })
    }
    document.querySelector("#admin_card_phone") ? document.querySelectorAll("#admin_card_phone").forEach(e => e.textContent = "رقم الجوال") : '';
    document.querySelector("#admin_card_comp_name") ? document.querySelectorAll("#admin_card_comp_name").forEach(e => e.textContent = "اسم الشركة") : '';
    document.querySelector("#admin_card_comp_number") ? document.querySelectorAll("#admin_card_comp_number").forEach(e => e.textContent = "رقم الشركة") : '';
    document.querySelector("#admin_card_show") ? document.querySelectorAll("#admin_card_show").forEach(e => e.textContent = "عرض") : '';
    document.querySelector("#admin_card_edit") ? document.querySelectorAll("#admin_card_edit").forEach(e => e.textContent = "تعديل") : '';
    document.querySelector("#admin_card_delete") ? document.querySelectorAll("#admin_card_delete").forEach(e => e.textContent = "حذف") : '';
    document.querySelector("#tasks_heading") ? tasks_heading.textContent = "المهام" : '';
    document.querySelector("#admins_show_tasks") ? admins_show_tasks.textContent = "عرض المهام" : '';
    document.querySelector("#tasks_add_task") ? tasks_add_task.textContent = "+ إضافة مهمة" : '';
    document.querySelector("#admins_no_data") ? admins_no_data.textContent = "لا يوجد بيانات" : '';
    document.querySelector("#tasks_start_date") ? document.querySelectorAll("#tasks_start_date").forEach(e => e.textContent = "تاريخ البداية") : '';
    document.querySelector("#tasks_end_date") ? document.querySelectorAll("#tasks_end_date").forEach(e => e.textContent = "تاريخ النهاية") : '';
    document.querySelector("#tasks_status") ? document.querySelectorAll("#tasks_status").forEach(e => e.textContent = "الحالة") : '';
    document.querySelector("#tasks_show") ? document.querySelectorAll("#tasks_show").forEach(e => e.textContent = "عرض") : '';
    document.querySelector("#tasks_edit") ? document.querySelectorAll("#tasks_edit").forEach(e => e.textContent = "تعديل") : '';
    document.querySelector("#tasks_delete") ? document.querySelectorAll("#tasks_delete").forEach(e => e.textContent = "حذف") : '';
    document.querySelector("#emp_heading") ? emp_heading.textContent = "الموظفين" : '';
    document.querySelector("#emp_add_emp") ? document.querySelectorAll("#emp_add_emp").forEach(e => e.textContent = "+ إضافة موظف") : '';
  }
  // END CHANGE LANGUAGE

};
function getPaddinglocalStorage() {
  if (localStorage.getItem("body-padding-top") == "true") {
    document.body.classList.add("body-padding-top");
  } else {
    document.body.classList.remove("body-padding-top");
  }
}

function sidebarLinks(dest) {
  document.querySelector("iframe").setAttribute("src", `src/${dest}`);
  if (document.body.offsetWidth <= 767.9) {
    document.querySelector(".navbar-toggler").click();
  }
}

function changeModalLang(){
  if(localStorage.getItem("vela-dash-lang") == "ar"){
    document.querySelector('#delete_modal_content') ? document.querySelectorAll('#delete_modal_content').forEach(e => e.textContent = "هل تريد حذف المشرف") : '';
    document.querySelector('#tasks_delete_modal_content') ? document.querySelectorAll('#tasks_delete_modal_content').forEach(e => e.textContent = "هل تريد حذف المهمة") : '';
    document.querySelector('#user_delete_modal_content') ? document.querySelectorAll('#user_delete_modal_content').forEach(e => e.textContent = "هل تريد حذف الموظف") : '';
    document.querySelectorAll('#questionMark').forEach(e => e.textContent = "؟");
    document.querySelectorAll('#delete_modal_no').forEach(e => e.textContent = "لا");
    document.querySelectorAll('#delete_modal_yes').forEach(e => e.textContent = "نعم");
  }
}
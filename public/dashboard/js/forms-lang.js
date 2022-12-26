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

      // START CHANGE LANGUAGE
      if (!localStorage.getItem("vela-dash-lang") || localStorage.getItem("vela-dash-lang") == "en") {
        document.documentElement.dir = "ltr";
          document.querySelector("#form_locale") ? form_locale.value = "en": '';

      } else {
        document.documentElement.dir = "rtl";
          document.querySelector("#form_add_admin") ? form_add_admin.textContent = "إضافة مشرف": '';
          document.querySelector("#form_locale") ? form_locale.value = "ar": '';

          document.querySelector("#form_full_name") ? form_full_name.innerHTML = 'الاسم رباعي <span style="color:red;">*</span>': '';
        document.querySelector("#form_add_email") ? form_add_email.innerHTML = 'البريد الالكتروني <span style="color:red;">*</span>': '';
        document.querySelector("#form_add_password") ? form_add_password.innerHTML = 'كلمة المرور <span style="color:red;">*</span>': '';
        document.querySelector("#form_add_phone") ? form_add_phone.innerHTML = 'رقم الجوال <span style="color:red;">*</span>': '';
        document.querySelector("#form_add_company") ? form_add_company.innerHTML = 'اسم الشركة <span style="color:red;">*</span>': '';
        document.querySelector("#form_add_job") ? form_add_job.innerHTML = 'الوظيفة <span style="color:red;">*</span>': '';
        document.querySelector("#form_add_pp") ? form_add_pp.innerHTML = 'الصورة الشخصية <span style="color:red;">*</span>': '';
        document.querySelector("#add_user_to_comp") ? add_user_to_comp.innerHTML = 'إضافة الى شركة <span style="color:red;">*</span>': '';
        document.querySelector("#form_add_btn") ? form_add_btn.innerHTML = 'إضافة': '';
        document.querySelector("#form_add_close") ? form_add_close.innerHTML = 'إلغاء': '';
        document.querySelector("#form_edit_btn") ? form_edit_btn.innerHTML = 'تعديل': '';
        document.querySelector("#form_edit_admin") ? form_edit_admin.innerHTML = 'تعديل مشرف': '';
        document.querySelector("#admin_profile_name") ? admin_profile_name.innerHTML = 'الاسم': '';
        document.querySelector("#admin_profile_phone") ? admin_profile_phone.innerHTML = 'رقم الجوال': '';
        document.querySelector("#admin_profile_job") ? admin_profile_job.innerHTML = 'الوظيفة': '';
        document.querySelector("#admin_profile_email") ? admin_profile_email.innerHTML = 'البريد الالكتروني': '';
        document.querySelector("#admin_profile_comp_number") ? admin_profile_comp_number.innerHTML = 'رقم الشركة': '';
        document.querySelector("#admin_profile_comp_name") ? admin_profile_comp_name.innerHTML = 'اسم الشركة': '';
        document.querySelector("#admin_profile_back") ? admin_profile_back.innerHTML = 'رجوع': '';
        document.querySelector("#add_task_title") ? add_task_title.textContent = 'العنوان': '';
        document.querySelector("#add_task_start_date") ? add_task_start_date.textContent = 'تاريخ البداية': '';
        document.querySelector("#add_task_end_date") ? add_task_end_date.textContent = 'تاريخ النهاية': '';
        document.querySelector("#add_task_desc") ? add_task_desc.textContent = 'الوصف': '';
        document.querySelector("#add_task_desc_holder") ? add_task_desc_holder.placeholder = "وصف المهمة": '';
        document.querySelector("#add_task_title_input") ? add_task_title_input.placeholder = "عنوان المهمة": '';
        document.querySelector("#add_task_add_users") ? add_task_add_users.textContent = 'إضافة مستخدمين:': '';
        document.querySelector("#add_task_no_emp") ? add_task_no_emp.textContent = 'لا يوجد موظفين': '';
        document.querySelector("#add_task_confirm") ? add_task_confirm.textContent = 'موافق': '';
        document.querySelector("#add_emp_heading") ? add_emp_heading.textContent = 'إضافة موظف': '';
        document.querySelector("#edit_emp_heading") ? edit_emp_heading.textContent = 'تعديل موظف': '';
        document.querySelector("#show_task_heading") ? show_task_heading.textContent = 'معلومات المهمة': '';
        document.querySelector("#show_task_status") ? show_task_status.textContent = 'حالة المهمة': '';
        document.querySelector("#show_task_employees") ? show_task_employees.textContent = 'الموظفين:': '';
        document.querySelector("#show_task_notes") ? show_task_notes.textContent = 'الملاحظات': '';
        document.querySelector("#show_task_close") ? show_task_close.textContent = 'إغلاق المهمة': '';
        document.querySelector("#show_task_back") ? show_task_back.textContent = 'رجوع': '';
        document.querySelector("#show_task_completed") ? document.querySelectorAll("#show_task_completed").forEach(e => e.textContent = 'تم إنجاز المهمة'): '';
        document.querySelector("#show_task_working") ? document.querySelectorAll("#show_task_working").forEach(e => e.textContent = 'لم يتم انجاز المهمة'): '';
        document.querySelector("#show_task_add_note") ? show_task_add_note.textContent = 'إضافة ملاحظة:': '';
        document.querySelector("#show_task_note_area") ? show_task_note_area.placeholder = 'إضافة ملاحظة': '';
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

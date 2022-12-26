<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Vela: الشركات</title>
    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400&display=swap"
      rel="stylesheet"
    />
    <!-- Font Awesome -->
      <link rel="stylesheet" href="{{asset('dashboard//css/all.min.css')}}" />
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="{{asset('dashboard/css/bootstrap.rtl.min.css')}}" />
      <!-- Custom styles -->
      <link rel="stylesheet" href="{{asset('dashboard/css/tables-style.css')}}" />
  </head>
  <body>
  <div class="loading-screen">
      <div
          class="spinner-border text-primary"
          style="width: 3rem; height: 3rem; color: var(--main-color) !important"
          role="status"
      >
          <span class="visually-hidden">Loading...</span>
      </div>
  </div>
  <h1 class="heading">المستخدمين</h1>
  <div class="table-container section-style">
      <div style="display: flex; align-items: center; margin-bottom: 10px; gap: 20px">
          <a href="#" class="btn btn-primary">+ إضافة مستخدم</a>
          <div class="input-group" style="width: 300px; height: 38px">
          <span class="input-group-text" id="basic-addon1">
            <button style="border: none">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </span>
              <input
                  type="text"
                  class="form-control"
                  placeholder="بحث"
                  aria-label="Username"
                  aria-describedby="basic-addon1"
              />
          </div>
      </div>
      <table class="table table-striped table-hover">
          <thead>
          <tr class="table-dark">
              <td scope="col">الرقم</td>
              <td scope="col">الباركود</td>
              <td scope="col">حالة الانبوبة</td>
              <td scope="col">سعر كيلو الغاز</td>
              <td scope="col">وزن الانبوبة كامل (كغم)</td>
              <td scope="col">كمية الغاز داخل الانبوبة (كغم)</td>
              <td scope="col">الاجراءات</td>
          </tr>
          </thead>
          <tbody>
          <tr>
              <td scope="row">1</td>
              <td>7635881</td>
              <td>ممتلئة</td>
              <td>20</td>
              <td>12</td>
              <td>10</td>
              <td>
                  <button type="button" class="btn btn-edit btn-sm">
                      <a href="#">
                          <i class="fa-solid fa-pen-to-square fa-lg"></i>
                      </a>
                  </button>
                  <button type="button" class="btn btn-edit btn-sm">
                      <a href="#">
                          <i class="fa-solid fa-eye fa-lg"></i>
                      </a>
                  </button>
                  <button
                      type="button"
                      class="btn btn-delete btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#deleteModal"
                  >
                      <i class="fa-solid fa-trash-can fa-lg"></i>
                  </button>
              </td>
          </tr>
          </tbody>
      </table>
  </div>
  <!-- DELETE MODAL -->
  <div
      class="modal fade"
      id="deleteModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
  >
      <div class="modal-dialog modal-dialog-centered modal-sm">
          <div class="modal-content">
              <div class="modal-header">
                  <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                  ></button>
              </div>
              <div class="modal-body">هل تريد حذف الصف؟</div>
              <div class="modal-footer">
                  <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                  >
                      لا
                  </button>
                  <button type="button" class="btn btn-primary">نعم</button>
              </div>
          </div>
      </div>
  </div>
    <script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dashboard/js/all.min.js')}}"></script>
    <script src="{{asset('dashboard/js/tables-script.js')}}"></script>
  </body>
</html>

@extends('layouts._layout')
@section('pageTitle', 'Home')
@section('content')

<!-- Main content -->
<?php
try {
  DB::connection()->getPdo();
} catch (\Exception $e) {
  ?>
  <div class="col-sm-12 alert alert-danger">
    <b>Sambungan Gagal !</b><br>
    Tidak dapat tersambung ke database, pesan error :<br>
    <?php echo $e->getMessage(); ?>
  </div>
  <?php
}
?>
<style>
  .toast-header{
    background-color: #c0c0c0;
  }
  .toast-body{
    background-color: #d8d8d8;
    word-wrap: break-word;
  }
  .mr-auto{
    word-wrap: break-word;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: sans-serif;
  }

  #toast-holder {
    position: fixed;
    left: 20px;
    top: 80px;
    width: 500px;
    display: flex;
    flex-direction: column;
  }

  .single-toast {
    width: 500px;
    border-radius: 5px;
    background-color: white;
    color: #5b5b5b;
    margin-bottom: 20px;
    box-shadow: 0 5px 10px rgba(0,0,0,.5);
    transition: .3s;
    /*max-height: 100px;*/
    display: flex;
    flex-direction: column;
  }

  .toast-header {
    display: flex;
    justify-content: space-between;
    padding: 5px 10px;
    border-bottom: 1px solid #ccc;
  }
  .close-toast {
    color: inherit;
    text-decoration: none;
    font-weight: bold;
  }
  .toast-content {
    padding: 10px 10px 5px;
  }

  .fade-in {
    animation: fadeIn linear .5s;
  }

  .fade-out {
    animation: fadeOut linear .5s;
  }

  @keyframes fadeIn {
    0% {
      opacity: 0;
      max-height: 0px;
    }
    
    100% {
      opacity: 1;
      max-height: 100px;
    }
  }

  @keyframes fadeOut {
    0% {
      opacity: 1;
      max-height: 100px;
    }
    100% {
      opacity: 0;
      max-height: 0;
    }
  }
</style>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->

<section class="content">
  <br>
  <center>
    <font style=" font-size: 40px">Selamat Datang</font><br>
    <b>
      <font style=" font-size: 30px"><?php echo Auth::user()->name;?></font>
    </b>
    <br><br><br>
    <img src="img/logo_univ.png" alt="" class="logo-side" >
    <br><br><br>
    <h1>SISTEM INFORMASI AKADEMIK</h1>
    <h3>{{env('NAME_UNIV')}}</h3>
  </center>

</section>
<template id='my-template'>
  <swal-title>Hey!</swal-title>
</template>
<!-- /.content -->
<script>
  $(document).ready(function(){
    function createToast(heading = "No heading", message = "No message", file="") {
      //Create empty variable for toasts container
      let container;
      //If container doesn't already exist create one
      if (!document.querySelector("#toast-holder")) {
        container = document.createElement("div")
        container.setAttribute("id", "toast-holder");
        document.body.appendChild(container);
      } else {
        // If container exists asign it to a variable
        container = document.querySelector("#toast-holder");
      }
      
      let toast = "";
      if(file == ""){
        //Create our toast HTML and pass the variables heading and message
        toast = `<div class="single-toast fade-in">
                        <div class="toast-header">
                          <span class="toast-heading">${heading}</span>
                          <a href="#" class="close-toast">X</a>
                        </div>
                        <div class="toast-content">
                          ${message}
                        </div>
                     </div>`;
      }else{
        toast = `<div class="single-toast fade-in">
                        <div class="toast-header">
                          <span class="toast-heading">${heading}</span>
                          <a href="#" class="close-toast">X</a>
                        </div>
                        <div class="toast-content">
                          ${message}
                          <br>
                          <a href="${file}" target="_blank">File</a>
                        </div>
                     </div>`;
      }
                   
      // Once our toast is created add it to the container
      // along with other toasts
      container.innerHTML += toast;
      
       
        //Save all those close buttons in one variable
        let toastsClose = container.querySelectorAll(".close-toast");
      
      //Loop thorugh that variable
      for(let i = 0; i < toastsClose.length; i++) {
          //Add event listener
        toastsClose[i].addEventListener("click", removeToast,false);
      }
      
    }

    function removeToast(e) {
      //First we need to prevent default
      // to evade any unexpected behaviour
      e.preventDefault();
      
      //After that we add a class to our toast (.single-toast)
      e.target.parentNode.parentNode.classList.add("fade-out");
      
      //After CSS animation is finished, remove the element
      setTimeout(function() {
        e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
     
         
        if (isEmpty("#toast-holder")) {
            console.log(isEmpty("#toast-holder"));
            document.querySelector("#toast-holder").parentNode.removeChild(document.querySelector("#toast-holder"));
        }
      }, 500);
    }
    function isEmpty(selector) {
      return document.querySelector(selector).innerHTML.trim().length == 0;
    }

    var obj = <?php echo webSetting::announcement(); ?>;
    $.each( obj, function( key, value ) {
      createToast(value.Announcement_Name, value.Message,value.File_Upload);
    });
  })
</script>
@endsection

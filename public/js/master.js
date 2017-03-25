$(document).ready(function(){
  //Search function
  //When the button is clicked, this will fetch
  //the search word and category entered by users.
  //Then the function will submit the form
  //after creating the action url.
  $('#search_btn').on('click',function(){
    var select_val = $('#selected').text();
    var search_word = $('#q').val();

    var action_url = '/board/lists/ci_board/q/'+select_val+'/'+search_word+'/page/1';
    if(search_word == ''){
      alert('Please enter the keyword');
      return false;
    }else{
      $('#searching').attr('action', action_url).submit();
    }
  });


  $('#file-upload').bind('change', function(){
    var fileName = '';
    fileName = $(this).val();
    console.log(fileName);
    var path_explode = fileName.lastIndexOf('\\');
    var len = fileName.length;
    var newPath = fileName.substring(path_explode+1,len);
    console.log(newPath);
    $('#selectedFile').html(newPath);
  });


  $('.dropdown-menu li').on('click', function(e){
    e.preventDefault();
    $current_val = '';
    $(this).toggleClass('active').siblings().removeClass('active');
      $current_val = $('.dropdown-menu li.active').text();
    console.log($current_val);
    $('span#selected').html($current_val);
  });


  $('#admin ul li').on('click', function(e){
    e.preventDefault();
    $(this).toggleClass('active').siblings().removeClass('active');

  });
});

//Function to make 'enter key' work
//on search input.
function search_enter(form) {
    var keycode = window.event.keyCode;
    if(keycode == 13){
    $("#search_btn").click();
    }
    return false;
}

//Function to get cookie
function getCookie(name){
  var nameOfCookie = name + "=";
  var x = 0;

  while ( x <= document.cookie.length ){
    var y = (x+nameOfCookie.length);
    if (document.cookie.substring( x, y ) == nameOfCookie ) {
      if ((endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
      endOfCookie = document.cookie.length;

      return unescape( document.cookie.substring( y, endOfCookie ) );
    }
    x = document.cookie.indexOf( " ", x ) + 1;
    if ( x == 0 )
    break;
  }
  return "";
}

for (var i = 0; i < document.querySelectorAll(".inputData").length; i++) {
  document.querySelectorAll(".inputData")[i].addEventListener("input", function(){
    if(this.value.trim().length===0){
      $("."+this.classList[1]).removeClass("original_numbers");
    }else {
      $("."+this.classList[1]).addClass("original_numbers");
    }
  });
}

<head>
  <title>Sudoku Solver</title>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  <div class="loader-wrapper">
     <span class="loader"><span class="loader-inner"></span></span>
   </div>
<?php
  if($_SERVER['REQUEST_METHOD']!='POST'){
    echo "<h1 class='title'>ONLINE SUDOKU SOLVER</h1><br/><h1 class='subtitle'>Solve any solvable sudoku instantly</h1><hr/><h3 style=color:darkgreen>Feed the sudoku clues below (numbers 1-9)</h3>
    <form action='index.php' method='post'><table>";
    for ($i=0; $i <9 ; $i++) {
      if (($i)%3==0 and $i!=0) {
        echo "<tr><td class='inputTableData'>&emsp;</td></tr>";
      }
      echo "<tr>";
      for ($j=0; $j < 9; $j++) {
        if (($j)%3==0 and $j!=0) {
          echo "<td class='inputTableData'>&emsp;</td>";
        }
        echo "<td class='inputTableData'><input class='inputData item-".(9*$i+$j)."' type='number' min=1 max=9 autocomplete=off name=board[]></td>";
      }
      echo "</tr>";
    }
    echo "</table>";
    echo "<input type='submit' value='SOLVE' class='btn btn-primary btn-lg'></form>";
    ?>
    <script src="index.js"></script>
    <?php
  }
  else{
    $original_numbers=array();
    $board;
    $answerArray=array();
    for ($i=0; $i<81; $i+=1){
      if(($_POST['board'][$i])!=''){
        $board[floor($i/9)][$i%9]=$_POST['board'][$i];
        array_push($original_numbers, $i);
      }else {
        $board[floor($i/9)][$i%9]=0;
      }
    }
    function isAvailable($i, $j, $num){
      global $board;
      for ($x=0; $x < 9; $x++) {
        if($board[$i][$x]==$num){
          return false;
        }
      }
      for ($x=0; $x < 9; $x++) {
        if($board[$x][$j]==$num){
          return false;
        }
      }
      for ($x=3*floor($i/3); $x < (3*floor($i/3))+3; $x++) {
        for ($y=3*floor($j/3); $y < (3*floor($j/3))+3; $y++) {
          if ($board[$x][$y]==$num) {
            return false;
          }
        }
      }
      return true;
    }

    function isSolved(){
      global $board;
      for ($i=0; $i < 9; $i++) {
        for ($j=0; $j < 9; $j++) {
          if($board[i][j]==0){
            return false;
          }
        }
      }
      return true;
    }
    $check=false;
    function solve(){
      global $board, $check;
      if ($check) {
        return;
      }
      for ($i=0; $i < 9 ; $i++) {
        for ($j=0; $j < 9; $j++) {
          if($board[$i][$j]==0){
            for ($k=1; $k < 10 ; $k++) {
              if(isAvailable($i, $j, $k)){
                $board[$i][$j]=$k;
                solve();
                $board[$i][$j]=0;
              }
            }
            return;
          }
        }
      }
      addAnswer();
      $check=true;
    }

    function addAnswer(){
      global $board, $answerArray;
      array_push($answerArray, $board);
    }

    function printBoard(){
      global $answerArray;
      echo "<table class='answerTable'>";
      for ($i=0; $i < 9; $i++) {
        if (($i)%3==0 and $i!=0) {
          echo "<tr><td class='blank'>&emsp;</td></tr>";
        }
        echo "<tr>";
        for ($j=0; $j < 9; $j++) {
          if (($j)%3==0 and $j!=0) {
            echo "<td class='answerTableData blank'>&emsp;</td>";
          }
          echo "<td class='answerTableData ".(9*$i+$j)."'>".$answerArray[0][$i][$j]."</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
    }

    function validBoard(){
      global $board;
      for ($i=0; $i < 9; $i++) {
        for ($j=0; $j < 9; $j++) {
          if ($board[$i][$j]!=0) {
            $temp=$board[$i][$j];
            $board[$i][$j]=0;
            if(!isAvailable($i, $j, $temp)){
              return false;
            }
            $board[$i][$j]=$temp;
          }
        }
      }
      return true;
    }

    if(validBoard()){
      solve($board);
      if (!count($answerArray)) {
        echo "No solutions found!<br>";
      }else {
        echo "<h1>The solution is given below!</h1>";
        printBoard();
      }
      echo "<h3><a href=index.php>Click here to try another sudoku!</a></h3>";
      echo "<script type='text/javascript'>";
      for ($i=0; $i < count($original_numbers); $i++) {
        echo "$('.".$original_numbers[$i]."').addClass('original_numbers');";
      }
      echo "</script>";
    }else {
      echo "<h3 class='alert alert-danger error'>You have provided an incorrect board!</h3>
	    <h4>- No 2 identical numbers must in the same row,column or subgrid<br>
		-Provide all clues between 1 to 9
		<br/>-It is generally preferred to provide atleast 17 workable clues<br/></h4>";
      echo "<h3><a href=index.php>Click here to try another sudoku!</a></h3>";
    }
  }
?>
<div class="container container-fluid credits">
  <h4 style=color:maroon>Created by Aman Parikh and Sahil Marwaha</h4>
  <h6 style=color:brown>Students at the Sardar Patel Institue of Technology</h6>
</div>
<script>
    $(window).on("load",function(){
      $(".loader-wrapper").fadeOut("slow");
    });
</script>
</body>

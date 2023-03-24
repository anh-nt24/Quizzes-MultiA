<link rel="stylesheet" href="./asset/css/create.css">
<link rel="stylesheet" href="./asset/css/quiz.css">
<link rel="stylesheet" href="./asset/css/responsive.css">

<?php
    if (isset($_GET['token'])) {
        $id = base64_decode($_GET['token']);
        $sql1 = "select * from Quiz where Quiz_id='".$id."'";
        $result = $conn->query($sql1);
        $quizData = $result->fetch_array();
        $sql2 = "select Question_id, question, score, type from Question where Quiz_id='".$id."'";
        $result = $conn->query($sql2);
        $questionData = [];
        if($result) {
            while($row = $result->fetch_array()){
                array_push($questionData, $row);
            }
        }
?>
<div id="page-container">
    <div class="row nopadding">
        <div class="quiz-timer w-100 fixed-top d-flex justify-content-center align-items-center">
            <div></div>
            <img style="width: 30px" src="./asset/img/clock.png" alt="">
            <span class="pl-2"></span>
        </div>
    </div>
    <div class="row nopadding">
        <form method="post" class="col-lg-10 col-12 m-auto">
            <div class="quiz-content">
                <div class="quiz-header pt-0">
                    <div class="form-group">
                        <span class="form-control" style="font-size: 3rem;" name="quiz-title" id="quiz-title">
<?php
                            echo $quizData['content'];
?>
                        </span>
                    </div>
                </div>
                <!-- <div class="quiz-form">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <label class="w-100" for="stating-date">
                                    Starting date: 
                                    <input id="stating-date" name="stating-date" class="w-100 form-control" type="datetime-local">
                                </label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="w-100" for="ending-date">
                                    Due to:
                                    <input id="ending-date" name="ending-date" class="w-100 form-control" type="datetime-local">
                                </label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="w-100" for="in-time">
                                    Time: 
                                    <input id="in-time" name="in-time" class="w-100 form-control" type="number" placeholder="  min(s)">
                                </label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="w-100" for="num-ques">
                                    Number of questions: 
                                    <input readonly id="num-ques" name="num-ques" class="w-100 form-control" type="text" onkeyup="generateQues(this)">
                                </label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="w-100" for="quiz-level">
                                    Difficulty:
                                    <input id="quiz-level" name="quiz-level" class="w-100 form-control" placeholder="0" type="number">
                                </label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="w-100" for="quiz-privacy">
                                    Privacy:
                                    <select name="quiz-privacy" class="form-control">
                                        <option value="1" selected> Public </option>
                                        <option value="2"> Private </option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                </div> -->

<?php
    $qsOrder = 1;
    $correctAnswer = [];
    foreach($questionData as $qs) {
        $queryCorr = "select * from Answer where Question_id = '".$qs['Question_id']."' and isCorrect = 1";
        $result = $conn->query($queryCorr);
        while($row = $result->fetch_array()){
            array_push($correctAnswer, $row['orderNum']);
        }
?>
                <div class="quiz-questions">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-11 col-sm-10 col-11 question__title">
                            <div class="form-group">
                                <span style="font-size: 22px;" class="form-control" name="ques-title[]" id="ques-title">
<?php
                                    echo $qs['question'];
?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-2 col-1">
                            <div class="form-group">
                                <span class="form-control scores" name="score[]">
<?php
                                    echo $qs['score'];
?>
                                <span>
                            </div>
                        </div>
                    </div>
                    <div class="row px-2">
                        <div class="col-12">
                            <div class="quiz-question__options">
                                <div class="row option">
<?php
        if ($qs['type'] == 2) {
?>

                                    <div class="col-12">
                                        <div class="options">
                                            <div class="form-group d-flex align-items-center">
                                                <i class="fa fa-pencil-square-o pr-3"></i>
                                                <input required type="text" class="form-control" name="ans">
                                            </div>
                                        </div>
                                    </div>
<?php
        }
        else {
            $sql = "select * from Answer where Question_id = '".$qs['Question_id']."'";
            $result = $conn->query($sql);
            $ansData = [];
            if($result) {
                while($row = $result->fetch_array()){
                    array_push($ansData, $row);
                }
            }
?>
                                    <div class="col-12">
                                        <div class="options">
<?php
            $order = 1;
            foreach($ansData as $ans) {
?>
                                            <div class="form-check">
                                                <label style="font-size: 18px;" class="form-check-label">
                                                    <input <?php if ($order==1) echo "required";?> value="<?php echo $order; $order+=1?>" type="radio" style="font-size: 15px;" class="form-check-input" name="<?php echo "ans".$qsOrder?>">
<?php
                                                    echo $ans["content"];
?>
                                                </label>
                                            </div>
<?php
            }
?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php
            $qsOrder += 1;
        }
?>
                </div>
<?php
    }
?>
                <div class="quiz-footer px-2 d-flex">
                    <button type="submit" name="turn-in" class="btn">Turn in</button>
                    <input class="ml-auto btn" type="reset" value="Clear form">
                </div>
            </div>
        </form>
    </div>
    <div id="modal-here">

    </div>
</div>
<script src="algorithm/countdown.js"></script>
<?php
    // echo "
    //     <script>
    //         countdown(".$quizData['duration'].");
    //     </script>
    // ";
?>

<?php
}
?>

<?php
    if (isset($_POST['turn-in'])) {
        $userAnswer = [];
        for ($i=1; $i<=count($questionData); $i++) {
            array_push($userAnswer, $_POST['ans'.$i]);
        }
        $totalGrade = 0;
        for ($i=0;$i<count($correctAnswer);$i++) {
            $score = $questionData[$i]['score'];
            if ($correctAnswer[$i] == $userAnswer[$i]) {
                $totalGrade += $score;
            }
        }
    }
?>

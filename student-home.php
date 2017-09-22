<?php


$rows = $PDOX->allRowsDie("SELECT * FROM flashcards_set where CourseName='".$_SESSION["CourseName"]."' AND Active=1 AND Visible=1");
echo "<h3>Welcome, ".$_SESSION["FullName"]."</h3><br>";
echo "<table class='table table-bordered table-hover'  width='600' cellpadding='10'";
echo "<tr><th>Flashcards</th><th>  </th><th></th></tr>";

foreach ( $rows as $row ) {
    echo "<tr>";
    echo "<td>".$row["CardSetName"]."</td>";
    echo "<td> <a href='playcard.php?SetID=".$row["SetID"]."&CardNum=1&Flag=A'>Study</a></td>";
    echo "<td> <a href='start.php?SetID=".$row["SetID"]."&CardNum=1&Flag=A'>Review</a></td>";

    echo "</tr>\n";
}
echo("</table>\n");
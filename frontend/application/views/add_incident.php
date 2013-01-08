<form action="<?=$_SERVER['PHP_SELF'] ?>" method="post">

    <div class="left">

    <label for="title">Title:
        <input type="text" name="title" id="title" />
    </label>

    <label for="location">Location:
        <input type="text" name="location" id="location" />
    </label>

    <label for="incidentdate">Date/time of incident:
        <input type="text" name="incidentdate" id="incidentdate" />
    </label>

    </div>

    <div class="right">

        

    </div>

    <input type="submit" value="Add incident" />

</form>

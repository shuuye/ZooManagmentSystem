<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the filters array is set and not empty
    if (isset($_POST['filters']) && !empty($_POST['filters'])) {
        // Count the number of selected checkboxes

        $filters = $_POST['filters'];
        if ($filters == "Animal") {
            
        }
        if ($filters == "Habitat") {
            header("Location: index.php?controller=inventory&action=generateReport&report=habitatinventorySummaryReport");
        }
        if ($filters == "Food") {
            header("Location: index.php?controller=inventory&action=generateReport&report=foodinventorySummaryReport");
        }
        if ($filters == "Cleaning") {
            header("Location: index.php?controller=inventory&action=generateReport&report=cleaninginventorySummaryReport");
        }  
        if ($filters == "outStock") {
            header("Location: index.php?controller=inventory&action=generateReport&report=outStockinventorySummaryReport");
        }  
        if ($filters == "inStock") {
            header("Location: index.php?controller=inventory&action=generateReport&report=inStockinventorySummaryReport");
        }  
        if ($filters == "lowStock") {
            header("Location: index.php?controller=inventory&action=generateReport&report=lowStockinventorySummaryReport");
        }  
        
      
    } else{
        header("Location: index.php?controller=inventory&action=generateReport&report=inventorySummaryReport");
    }
}    
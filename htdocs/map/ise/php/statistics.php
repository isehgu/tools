<?php
    require_once("statistics.php");

    function f_displayStatisticsSummary(){
        echo '
            <div class="row">
                <div class="small-12 columns">
                    <h3>Events Per Release</h3>
                    <iframe height="400" width="1000" frameborder="0" src="http://id-imap01.inf.ise.com:8000/en-US/embed?s=%2FservicesNS%2Fdevops%2Fise_bdt%2Fsaved%2Fsearches%2FEvents%2520Per%2520Release&oid=gc_AxjtIYC9ZKX732z8DeOFGh7HFRQXKYYLpNHNhnmtgAtswHbdiPB9Qxruow%5EGr_RkbXcCNYPOTeOEq%5EgSa2V18g9UkjJg1E2E1no76LjumeTdeSVqSVn5TkOwkWQagPtVtz700ykWmh1yLdw7">
                    </iframe>
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <h3>Deployment Durations</h3>
                    <iframe height="636" width="1000" frameborder="0" src="http://id-imap01.inf.ise.com:8000/en-US/embed?s=%2FservicesNS%2Fdevops%2Fise_bdt%2Fsaved%2Fsearches%2FDeployment%2520Times%2520Table&oid=82Lb4J53Zl778lbyBdjy3bWbQlQBioNkGlpb8q33AdGbEUc_Irb4TjUhjJ_QeWyualz45ydQhY1FzIIT%5EE_RSeXVOS4c5wF3qdaqrrEstBYLf7f1p1gCdP4cDZqsrhrbBfYh4de%5E9a%5ESG52rWlqsnqbZoWY">
                    </iframe>
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">
                    <h3>Test Case Run Status Over Time</h3>
                    <iframe height="336" width="1000" frameborder="0" src="http://id-imap01.inf.ise.com:8000/en-US/embed?s=%2FservicesNS%2Fdevops%2Fise_bdt%2Fsaved%2Fsearches%2FTest%2520Case%2520Run%2520Status%2520Over%2520Time&oid=mW9ZaxHRBq9Qg8T9wKmAccZPAke_qYZksl6bWY6YyI2n69KlImqyDncsH7HzZPTPxfC8kGfrj4Md6wCmQVdNSKrcvqJcLLFOpnTsbt%5E11wuC3S5ZUm8lXhjyyt9EbyJINHQLdPVYYEUoS%5E%5EVuKyPXRo4w407jrF">
                    </iframe>
                </div>
            </div>

            <div class="row">
                <div class="small-6 columns">
                    <h3>Event Frequency</h3>
                    <iframe height="336" width="480" frameborder="0" src="http://id-imap01.inf.ise.com:8000/en-US/embed?s=%2FservicesNS%2Fdevops%2Fise_bdt%2Fsaved%2Fsearches%2FEvents&oid=prGqdals5bJheI3AqeFSRPlXv08c1OZxPdoIi_MXJ02GFoQiPcu1cb6RQy6luMQrz0dq2llbl1D7HP5UETo76YBuEBd3KY5gXqiqcMf0gs65OCrN4t_6Wn5cDFDfqv7%5Evsr">
                    </iframe>
                </div>
            </div>

        ';
    }

    function f_displayStatisticsDetailed(){
        echo "<p style='text-align: center'>Coming Soon</p>";
    }
?>

<?php foreach ($sqlTest as $key => $test): ?>
            <?php
                $headerArr = isset($test['result'][0]) ? $test['result'][0] : [];
                $sqlHeader = array_keys($headerArr);
            ?>
            <div class="sql-task"><?= $test['message'] ?></div>
            <div class="sql-query"><?= $test['sql'] ?></div>
            <br/>
            <div>
                <table class="content-block" cellspacing="0" border="1px">
                    <thead>
                        <?php foreach ($sqlHeader as $keyH => $header): ?>
                           <td class="result-head"><?= $header; ?></td>
                        <?php endforeach; ?>
                    </thead>
                    <?php foreach ($test['result'] as $keyR => $res): ?>
                        <tbody>
                            <?php foreach ($res as $keyF => $field): ?>
                                <td class="result-query"><?= $field ?></td>
                            <?php endforeach; ?>
                        </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
            <br/>
            <br/>
        <?php endforeach; ?>

<style>
    .content-block {
        border-radius: 0 0 15px 15px;
        -moz-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
        -webkit-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
        border: 0.5px solid #eef1f0;
        background: white;
        width: 100%;
        max-width: 700px;
        margin: auto;
        overflow: hidden;
        color: #9d9d9d;
    }
    .result-query, .result-head, .sql-query, .sql-task {
        text-align: center;
    }
</style>

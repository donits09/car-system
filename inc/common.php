<?php 



function ftom($value) {
    return number_format($value, 2);
}

function mtof($value) {
    $m_value = str_replace(",", "", $value);
    // Convert to float and format with two decimal places
    return (float)$m_value;
}
function ftoa($f_value) {
    return number_format((float)$f_value, 2, '.', '');
}


$g_site = [];
$g_acro = [];

function get_site_code($acronym, $cnx) {
    global $g_site;

    if (empty($g_site)) {
        $sql = "SELECT c_acronym, c_code FROM t_projects ORDER BY c_acronym ASC";
        $result = $cnx->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $acronym_strip = strtoupper(str_replace('-', '', $row['c_acronym']));
                $g_site[$acronym_strip] = $row['c_code'];
            }
        }
    }

    $acronym_strip = strtoupper(str_replace('-', '', $acronym));
    return array_key_exists($acronym_strip, $g_site) ? $g_site[$acronym_strip] : 0;
}

function get_site_acronym($code, $cnx) {
    global $g_acro;

    if (empty($g_acro)) {
        $sql = "SELECT c_code, c_acronym FROM t_projects ORDER BY c_code ASC";
        $result = $cnx->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $g_acro[$row['c_code']] = $row['c_acronym'];
            }
        }
    }

    return array_key_exists($code, $g_acro) ? $g_acro[$code] : 'none';
}

function get_site_name($code, $cnx) {
    $sql = sprintf("SELECT c_name FROM t_projects WHERE c_code = '%s'", $cnx->real_escape_string($code));
    $result = $cnx->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['c_name'];
    } else {
        return 'None';
    }
}


?>
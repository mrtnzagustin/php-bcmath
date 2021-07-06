<?php
/**
 * Chequea si un numero string es negativo a partir de la primera posicion del mismo
 */
function bcnegative($number)
{
    // Para ser negativo debe tener un guion en su primera posicion
    return strpos($number, '-') === 0;
}

/**
 * Chequea si un numero string es decimal o entero
 */
function bcisdecimal($number)
{
    // Para ser decimal debe poseer el punto
    return strpos($number, '.');
}

/**
     * Redondea un numero a partir de lib bcmath
     *
     * Explicacion:
     * $numeroEjemplo = "320.1357"
     * 1) Crea un numero "$fix" que terminara siendo de la forma:
     *      - 005 para por ej, una escala de 2
     *      - 0005 para por ej, una escala de 3
     * 2) Luego, al numero que se quiere redondear le suma (o resta) el valor de 0.$fix, usando bcadd (o bcsub) con una escala aumentada en 1:
     *      - 320.1357 + 0.005 para por ej, una escala de 2 -> = 320.1407
     *      - 320.1357 + 0.0005 para por ej, una escala de 3 -> = 320.1362
     * 3) Finalmente recorta la parte decimal del numero a la cantidad de digitos indicada por $scale
     *      - 320.14 para una escala de 2
     *      - 320.136 para una escala de 3
     * Ademas: controla que el numero sea decimal (y no entero)
     */
function bcround($number, $scale = 0)
{
    // Si el numero tiene parte decimal, se procesa
    if (bcisdecimal($number)) {
        // Calcula el $fix a sumar o restar al valor para redondear
        $fix = '5';
        for ($i = 0; $i < $scale; $i++) {
            $fix = "0$fix";
        }
        // Si es negativo, resta el fix
        if (bcnegative($number)) {
            $number = bcsub($number, "0.$fix", $scale + 1);
        } else {// Si es positivo, suma el fix
            $number = bcadd($number, "0.$fix", $scale + 1);
        }
        // Recorta la parte decimal a la cantidad de posiciones de $scale
        list($int, $decimal) = explode('.', $number);
        $decimal = substr($decimal, 0, $scale);
        return "$int.$decimal";
    }
    // Si el numero es un numero entero lo devuelve igual
    return $number;
}

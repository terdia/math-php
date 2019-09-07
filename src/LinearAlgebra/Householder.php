<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

class Householder
{
    /**
     * Householder Matrix
     *
     * u = x ± αe   where α = ‖x‖ and sgn(α) = sgn(x)
     *
     *              uuᵀ
     * Q = I - 2 * -----
     *              uᵀu
     *
     * @param Matrix $A source Matrix
     *
     * @return Matrix
     *
     * @throws Exception\MathException
     */
    public static function transform(Matrix $A): Matrix
    {
        $m = $A->getM();
        $I = MatrixFactory::identity($m);

        // x is the leftmost column of A
        $x = $A->submatrix(0, 0, $m - 1, 0);

        // α is the square root of the sum of squares of x with the correct sign
        $sign = $x[0][0] >= 0 ? 1 : -1;
        $α = $sign * $x->frobeniusNorm();

        // e is the first column of I
        $e = $I->submatrix(0, 0, $m - 1, 0);

        // u = x ± αe
        $u   = $e->scalarMultiply($α)->add($x);
        $uᵀ  = $u->transpose();
        $uᵀu = $uᵀ->multiply($u)->get(0, 0);
        $uuᵀ = $u->multiply($uᵀ);
        if ($uᵀu == 0) {
            return $I;
        }

        // We scale $uuᵀ and subtract it from the identity matrix
        return $I->subtract($uuᵀ->scalarMultiply(2 / $uᵀu));
    }
}

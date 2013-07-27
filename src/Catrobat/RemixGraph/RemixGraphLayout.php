<?php

namespace App\Catrobat\RemixGraph;

class RemixGraphLayout
{
  public static array $REMIX_GRAPH_MAPPING = [[
    //--------------------------------------------------------------------------------------------------------------
    // USAGE: php bin/console catrobat:reset --hard --remix-layout=1
    //--------------------------------------------------------------------------------------------------------------
    //                    (1)    (2)
    //                     |      |
    //                    (3)    (4)
    //                      \     /
    //                        (5)
    //                      /  |  \
    //                    (6) (7)  ...
    //--------------------------------------------------------------------------------------------------------------
    '1,0' => [3],
    '2,0' => [4],
    '3,0' => [5],
    '4,0' => [5],
    '5,0' => [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
  ], [
    //--------------------------------------------------------------------------------------------------------------
    // USAGE: php bin/console catrobat:reset --hard --remix-layout=2
    //--------------------------------------------------------------------------------------------------------------
    //                      (1)
    //                      / \
    //                    (2) (3)
    //                      \ /
    //                      (4)
    //--------------------------------------------------------------------------------------------------------------
    '1,0' => [2, 3],
    '2,0' => [4],
    '3,0' => [4],
  ], [
    //--------------------------------------------------------------------------------------------------------------
    // USAGE: php bin/console catrobat:reset --hard --remix-layout=3
    //--------------------------------------------------------------------------------------------------------------
    //                      (1) <-----
    //                      / \      |
    //                    (2) (3)    |
    //                      \        |
    //                      (4)______/
    //
    //--------------------------------------------------------------------------------------------------------------
    // NOTE: here the edge from 4 to 1 differs from all other, because it is directed backwards/upwards.
    //       All other edges are directed downwards/forwards.
    //--------------------------------------------------------------------------------------------------------------
    '1,0' => [2, 3],
    '2,0' => [4],
    '4,0' => [1],
  ], [
    //--------------------------------------------------------------------------------------------------------------
    // USAGE: php bin/console catrobat:reset --hard --remix-layout=4
    //--------------------------------------------------------------------------------------------------------------
    //                (1)      (2)
    //                  \    /  |  \
    //                   \  /   |   \
    //                   (3)    /  (4)
    //                     \   /    |
    //                      \ /     |
    //                      (5)    (6)
    //                     /   \   /
    //                   (7)    (8)
    //--------------------------------------------------------------------------------------------------------------
    '1,0' => [3],
    '2,0' => [3, 4, 5],
    '3,0' => [5],
    '4,0' => [6],
    '5,0' => [7, 8],
    '6,0' => [8],
  ], [
    //--------------------------------------------------------------------------------------------------------------
    // USAGE: php bin/console catrobat:reset --hard --remix-layout=5
    //--------------------------------------------------------------------------------------------------------------
    //                (1)    (SCRATCH) ___     (7)     (8)      (9)
    //                  \    /  |  \      \      \    /   \    /   \
    //                   \  /   |   \      |      (10)     (11)    (12)  (13)
    //                   (2)    /  (3)     |                         \    /
    //                  /  \   /__/ |      |                          (14)   (SCRATCH #2)
    //                 |    \ /     |      |                            \    /
    //                 |    (4)    (5)____/|                             (15)
    //                 |      \    /       |
    //                  \______ (6) _______/
    //--------------------------------------------------------------------------------------------------------------
    '1,0' => [2],
    '29495624,1' => [2, 3, 4, 5, 6],
    '2,0' => [4, 6],
    '3,0' => [4, 5],
    '4,0' => [6],
    '5,0' => [6],
    '7,0' => [10],
    '8,0' => [10, 11],
    '9,0' => [11, 12],
    '12,0' => [14],
    '13,0' => [14],
    '14,0' => [15],
    '120352193,1' => [15],
  ], [
    //--------------------------------------------------------------------------------------------------------------
    // USAGE: php bin/console catrobat:reset --hard --remix-layout=6
    //--------------------------------------------------------------------------------------------------------------
    //             (1) (SCRATCH #1)
    //               \ /
    //               (2)_____
    //               / \     \
    //             (3) (4)   |
    //              | \ |    |
    //             (5) (6)__/|          (8)  (SCRATCH #2)
    //               \ /     |            \   /
    //               (7)     |             (9)
    //                |      |              |
    //              (10)____/_______________/
    //
    //--------------------------------------------------------------------------------------------------------------
    '1,0' => [2],
    '29495624,1' => [2],
    '2,0' => [3, 4, 6, 10],
    '3,0' => [5, 6],
    '4,0' => [6],
    '5,0' => [7],
    '6,0' => [7],
    '7,0' => [10],
    '8,0' => [9],
    '120352193,1' => [9],
    '9,0' => [10],
  ]];
}

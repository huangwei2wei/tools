<?php
//8:3
////第一圈
//9:4  各+1  右下 1
//9:3  x+1    右上2
//8:2  y-1   正上3
//7:3  x-1   左上4
//7:4  x-1  y+1 左下5
//8:4  y+1  正下6
////第二圈
//9:5   x+1 y+2 7
//10:4  x+2 y+1 8
//10:3  x+2 9
//10:2  x+2 y-1 10
//9:2   x+1 y-2 11
//8:1   y-2 12
//7:2   x-1 y-1 13
//6:2   x-2 y-1 14
//6:3   x-2 15
//6:4   x-2 y+1 16
//7:5   x-1 y+2 17
//8:5   y+2 18

//7:3
//
//8:3	x+1	1
//8:2	x+1,y-1	2
//7:2	y-1	3
//6:2	x-1,y-1	4
//6:3	x-1	5
//7:4	y+1	6
//
//8:4	x+1,y+1	7
//9:4	x+2,y+1	8
//9:3	x+2	9
//9:2	x+2,y-1	10
//8:1	x+1,y-2	11
//7:1	y-2	12
//6:1	x-1,y-2	13
//5:2	x-2,y-1	14
//5:3	x-2	15
//5:4	x-2,y+1	16
//6:4	x-1,y+1	17
//7:5	y+2	18

switch ($number_grid)
{
	case 1:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]+1);
    			}else{
    				$res=($tmpdata[0]).":".($tmpdata[1]+1);
    			}
    		break;
    case 2:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]);
    			}
    		break;
    case 3:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]-1);
    			}else{
    				$res=($tmpdata[0]).":".($tmpdata[1]-1);
    			}
    		break;
    case 4:
    			if(!$act)
    			{
    				$res=($tmpdata[0]).":".($tmpdata[1]-1);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]-1);
    			}
    		break;
    case 5:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]);
    			}
    		break;
    case 6:
    			if(!$act)
    			{
    				$res=($tmpdata[0]).":".($tmpdata[1]+1);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]+1);
    			}
    		break;
    case 7:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]+2);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]+2);
    			}
    		break;
    case 8:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]+1);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]+1);
    			}
    		break;
    case 9:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]);
    			}
    		break;
    case 10:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]-1);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]-1);
    			}
    		break;
    case 11:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]-2);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]-2);
    			}
    		break;
    case 12:
    			if(!$act)
    			{
    				$res=($tmpdata[0]).":".($tmpdata[1]-2);
    			}else{
    				$res=($tmpdata[0]).":".($tmpdata[1]-2);
    			}
    		break;
    case 13:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]-2);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]-2);
    			}
    		break;
    case 14:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]-1);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]-1);
    			}
    		break;
    case 15:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]);
    			}
    		break;
    case 16:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]+1);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]+1);
    			}
    		break;
    case 17:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]+2);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]+2);
    			}
    		break;
    case 18:
    			if(!$act)
    			{
    				$res=($tmpdata[0]).":".($tmpdata[1]+2);
    			}else{
    				$res=($tmpdata[0]).":".($tmpdata[1]+2);
    			}
    		break;
    case 19:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]+3);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]+3);
    			}
    		break;
    case 20:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]+2);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]+2);
    			}
    		break;
    case 21:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]+1);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]+1);
    			}
    		break;
    case 22:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]);
    			}else{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]);
    			}
    		break;
    case 23:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]-1);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]-1);
    			}
    		break;
    case 24:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]-2);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]-2);
    			}
    		break;
    case 25:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]-3);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]-3);
    			}
    		break;
    case 26:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]-3);
    			}else{
    				$res=($tmpdata[0]).":".($tmpdata[1]-3);
    			}
    		break;
    case 27:
    			if(!$act)
    			{
    				$res=($tmpdata[0]).":".($tmpdata[1]-3);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]-3);
    			}
    		break;
    case 28:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]-3);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]-3);
    			}
    		break;
    case 29:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]-2);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]-2);
    			}
    		break;
    case 30:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]-1);
    			}else{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]-1);
    			}
    		break;
    case 31:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]);
    			}else{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]);
    			}
    		break;
    case 32:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]+1);
    			}else{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]+1);
    			}
    		break;
    case 33:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]+2);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]+2);
    			}
    		break;
    case 34:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]+3);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]+3);
    			}
    		break;
    case 35:
    			if(!$act)
    			{
    				$res=($tmpdata[0]).":".($tmpdata[1]+3);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]+3);
    			}
    		break;
    case 36:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]+3);
    			}else{
    				$res=($tmpdata[0]).":".($tmpdata[1]+3);
    			}
    		break;
    case 37:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]+4);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]+4);
    			}
    		break;
    case 38:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]+3);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]+3);
    			}
    		break;
    case 39:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]+2);
    			}else{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]+2);
    			}
    		break;
    case 40:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+4).":".($tmpdata[1]+1);
    			}else{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]+1);
    			}
    		break;
    case 41:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+4).":".($tmpdata[1]);
    			}else{
    				$res=($tmpdata[0]+4).":".($tmpdata[1]);
    			}
    		break;
    case 42:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+4).":".($tmpdata[1]-1);
    			}else{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]-1);
    			}
    		break;
    case 43:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]-2);
    			}else{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]-2);
    			}
    		break;
    case 44:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+3).":".($tmpdata[1]-3);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]-3);
    			}
    		break;
    case 45:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]-4);
    			}else{
    				$res=($tmpdata[0]+2).":".($tmpdata[1]-4);
    			}
    		break;
    case 46:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]-4);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]-4);
    			}
    		break;
    case 47:
    			if(!$act)
    			{
    				$res=($tmpdata[0]).":".($tmpdata[1]-4);
    			}else{
    				$res=($tmpdata[0]).":".($tmpdata[1]-4);
    			}
    		break;
    case 48:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]-4);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]-4);
    			}
    		break;
    case 49:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]-4);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]-4);
    			}
    		break;
    case 50:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]-3);
    			}else{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]-3);
    			}
    		break;
    case 51:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]-2);
    			}else{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]-2);
    			}
    		break;
    case 52:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]-1);
    			}else{
    				$res=($tmpdata[0]-4).":".($tmpdata[1]-1);
    			}
    		break;
    case 53:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-4).":".($tmpdata[1]);
    			}else{
    				$res=($tmpdata[0]-4).":".($tmpdata[1]);
    			}
    		break;
    case 54:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]+1);
    			}else{
    				$res=($tmpdata[0]-4).":".($tmpdata[1]+1);
    			}
    		break;
    case 55:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]+2);
    			}else{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]+2);
    			}
    		break;
    case 56:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]+3);
    			}else{
    				$res=($tmpdata[0]-3).":".($tmpdata[1]+3);
    			}
    		break;
    case 57:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]+4);
    			}else{
    				$res=($tmpdata[0]-2).":".($tmpdata[1]+4);
    			}
    		break;
    case 58:
    			if(!$act)
    			{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]+4);
    			}else{
    				$res=($tmpdata[0]-1).":".($tmpdata[1]+4);
    			}
    		break;
    case 59:
    			if(!$act)
    			{
    				$res=($tmpdata[0]).":".($tmpdata[1]+4);
    			}else{
    				$res=($tmpdata[0]).":".($tmpdata[1]+4);
    			}
    		break;
    case 60:
    			if(!$act)
    			{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]+4);
    			}else{
    				$res=($tmpdata[0]+1).":".($tmpdata[1]+4);
    			}
    		break;
}
?>
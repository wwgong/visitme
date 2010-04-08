/*
Copyright 2010 GoPandas
This file is part of Meet Me Half Way ( an extension of VisitME ).

VisitME is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation, either version 3 of the License, or (at your
option) any later version.

VisitME is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
for more details.

You should have received a copy of the GNU General Public License
along with VisitME. If not, see http://www.gnu.org/licenses/.
*/
function changeSection(section)
{
    if(section == 'searchProgress')
    {
         document.getElementById('searchProgress').className = "activeSection";
         document.getElementById('contents').className = "inactiveSection";
    }
    else if(section == 'contents')
    {
         document.getElementById('searchProgress').className = "inactiveSection";
         document.getElementById('contents').className = "activeSection";
    }
    else
    {
         document.getElementById('searchProgress').className = "inactiveSection";
         document.getElementById('contents').className = "inactiveSection";
    }
}


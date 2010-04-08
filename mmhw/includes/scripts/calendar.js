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
function repopulate_month(year)
{
    // Today date
    var currDay = new Date();
    var currMonth = currDay.getMonth(); // return month = 0 to 11
    var currYear = currDay.getFullYear();

    var months = new Array(12);
    months[0]  = "January";
    months[1]  = "February";
    months[2]  = "March";
    months[3]  = "April";
    months[4]  = "May";
    months[5]  = "June";
    months[6]  = "July";
    months[7]  = "August";
    months[8]  = "September";
    months[9]  = "October";
    months[10] = "November";
    months[11] = "December";

    var isFirstOpt = true;
    
    // Clear options - document.myform.name.options.length
    document.input.tmonth.options.length = 0;

    if(year == currYear)
    {
        for(var i=currMonth; i<12; i++)
        {
            if(isFirstOpt)
            {
                // new Option(text, value, defaultSelected, selected)
                // i-currMonth = to start from index 0
                document.input.tmonth.options[i-currMonth] = new Option(months[i], i+1, true, true);
                isFirstOpt = false;
            }
            else
            {
                document.input.tmonth.options[i-currMonth] = new Option(months[i], i+1, false, false);
            }
        }
    }
    else if(year == currYear+1)
    {
        for(var j=0; j<=currMonth; j++)
        {
            if(isFirstOpt)
            {
                // new Option(text, value, defaultSelected, selected)
                document.input.tmonth.options[j] = new Option(months[j], j+1, true, true);
                isFirstOpt = false;
            }
            else
            {
                document.input.tmonth.options[j] = new Option(months[j], j+1, false, false);
            }
        }
    }
    else
    {
        alert("Invalid year: " + year);
    }

    return;
}
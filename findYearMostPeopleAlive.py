#!/usr/bin/python

#Randall Meyer 4/16/2015

import os, sys

MinBirthEndYear = 1900 #Minimum Birthdate or Minimum End of life Year
MaxBirthEndYear = 2000 #Maximum Birthdate or Maximum End of life Year
yearWithNumberPeopleAlive = {} #Dictionary to hold values of number of people alive

#initializing Dictionary with Zero Values
for i in range(MinBirthEndYear, MaxBirthEndYear + 1):
    yearWithNumberPeopleAlive[i] = 0

f = open("people.txt", "r")

for line in f.readlines():
    line = line.strip()
    data = line.split(" ")
    data = filter(None, data)

    birthYear = int(data[1])
    endYear = int(data[2])

    for x in range(birthYear, endYear + 1):
        for year, peopleAlive in yearWithNumberPeopleAlive.iteritems():
            if x == year:
                yearWithNumberPeopleAlive[x] = peopleAlive + 1

#Checking for the year with the most people alive
yearWithMostPeopleAlive = MinBirthEndYear
for year, peopleAlive in yearWithNumberPeopleAlive.iteritems():
    if yearWithNumberPeopleAlive[yearWithMostPeopleAlive] < peopleAlive:
        yearWithMostPeopleAlive = year

print "The year with the most people alive is " + str(yearWithMostPeopleAlive),
print "With " + str(yearWithNumberPeopleAlive[yearWithMostPeopleAlive]) + " still living."

f.close()
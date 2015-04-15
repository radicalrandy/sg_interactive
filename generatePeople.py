#!/usr/bin/python

#Randall Meyer 4/16/2015

import os, string, random

MinBirthEndYear = 1900 #Minimum Birthdate or Minimum End of life Year
MaxBirthEndYear = 2000 #Maximum Birthdate or Maximum End of life Year
MinNameLength = 4      #Minimum Name Length
MaxNameLength = 10     #Maximum Name Length
NoPeople = 100         #Number of People

f = open("people.txt", "w")

#Generating Random People
for i in range(0, NoPeople):

    #Generating Random Name
    nameLength = random.randrange(MinNameLength, MaxNameLength + 1)
    name = ""
    for i in range(0, nameLength):
        name = name + random.choice(string.letters)

    #Generating Random Birth Year
    birthYear = random.randrange(MinBirthEndYear, MaxBirthEndYear + 1)

    #Generating Random End of Life Year
    endYear = MinBirthEndYear - 1
    while endYear < birthYear:
        endYear = random.randrange(MinBirthEndYear, MaxBirthEndYear + 1)

    f.write('{:10s} {:4d} {:4d}'.format(name, birthYear, endYear) + '\n')

f.close()
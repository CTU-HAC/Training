#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>

void init(void){
	setvbuf(stdin, NULL, _IONBF, 0);
	setvbuf(stdout, NULL, _IONBF, 0);
	setvbuf(stderr, NULL, _IONBF, 0);
}

void interview(void) {
	char wish[100];
	char mssv[10];
	unsigned int age;
	char name[50];

	printf("Your name: ");
	scanf("%s", name);

	printf("Your age: ");
	scanf("%u", &age);
	getchar();

	printf("MSSV: ");
	scanf("%10s", mssv);
	getchar();

	printf("Your wish: ");
	fgets(wish, sizeof(wish), stdin);
	if(wish[strlen(wish) - 1] == '\n')
		wish[strlen(wish) - 1] = '\0';	

	puts("Thanks for response!");
}

int main() {
	init();
	puts("Welcome to CTU_HAC pwnable training Day3!");
	puts("I want to know more about you.");
	interview();
	puts("You didn't passed Day3 :((");
	printf("Bye!");
	return 0;
}

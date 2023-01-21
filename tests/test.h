// define
#define ONE 1
#define TWO ((1+3)-2)
#define THREE ((int32_t)-1)

// type definitions
// built in
typedef int int_t;
typedef float float_t;
typedef char* char_t;

// pointer
typedef void* foo;

// struct
typedef struct a a_t;
typedef struct b {
    a_t *field1;
    a_t *field2;
} b_t;

// enum
typedef enum qux {
   QUUX,
   CORGE,
} grault;

// array
typedef unsigned char bar[23];
typedef long baz[][];

// function pointer
typedef char *(*(**hairy[][8])())[];

// function
extern char** func1(char_t arg1, hairy arg2);
const char* func2(const char* arg1, char* arg2);
void func3(void);